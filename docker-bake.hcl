# Builds the images of all repositories with docker buildx bake, with the tag
# dev. The images are for trying and testing empiriq, so they have no
# versions.
#   make build                         all images, from the repositories next to this one
#   make build S=board                 one image
#   make build IMAGE_SOURCE=git        from GitHub, the default branch
#
# The Makefile exports the variables of .env.

variable "IMAGE_SOURCE" {
  # local: the repositories next to this one. git: GitHub, the default branch.
  default = "local"
}
variable "IMAGE_GITHUB_ORG" { default = "empiriq" }
variable "IMAGE_REGISTRY" {
  # Prefix of the image names, for example "ghcr.io/<org>/". Empty: local images.
  default = ""
}

variable "DATABASE_DIR" { default = "../database" }
variable "BINANCE_BACKFILLER_DIR" { default = "../binance-backfiller" }
variable "BINANCE_FEEDER_DIR" { default = "../binance-feeder" }
variable "BOARD_DIR" { default = "../board" }

# Git revisions for the image labels. The Makefile sets them from the
# repositories next to this one.
variable "DATABASE_REVISION" { default = "" }
variable "BINANCE_BACKFILLER_REVISION" { default = "" }
variable "BINANCE_FEEDER_REVISION" { default = "" }
variable "BOARD_REVISION" { default = "" }

function "src" {
  params = [repo, dir]
  result = IMAGE_SOURCE == "git" ? "https://github.com/${IMAGE_GITHUB_ORG}/${repo}.git" : dir
}

function "image" {
  params = [name]
  result = "${IMAGE_REGISTRY}empiriq/${name}:dev"
}

function "build_args" {
  params = [revision]
  result = { VERSION = "dev", REVISION = revision != "" ? revision : "unknown" }
}

group "default" {
  targets = ["database", "binance-backfiller", "binance-feeder", "board"]
}

target "database" {
  context = src("database", DATABASE_DIR)
  target  = "database"
  args    = build_args(DATABASE_REVISION)
  tags    = [image("database")]
}

target "binance-backfiller" {
  context = src("binance-backfiller", BINANCE_BACKFILLER_DIR)
  args    = build_args(BINANCE_BACKFILLER_REVISION)
  tags    = [image("binance-backfiller")]
}

target "binance-feeder" {
  context = src("binance-feeder", BINANCE_FEEDER_DIR)
  args    = build_args(BINANCE_FEEDER_REVISION)
  tags    = [image("binance-feeder")]
}

target "board" {
  context = src("board", BOARD_DIR)
  args    = build_args(BOARD_REVISION)
  tags    = [image("board")]
}
