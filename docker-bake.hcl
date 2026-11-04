# Builds the images of all repositories with docker buildx bake.
#   make build                    all images, from the repositories next to this one
#   make build S=board            one image
#   make build SOURCE=git         from GitHub, at the tags of versions.env
#
# The Makefile exports the variables from versions.env and .env.

variable "SOURCE" {
  # local: the repositories next to this one. git: GitHub, at the version tag.
  default = "local"
}
variable "GITHUB_ORG" { default = "empiriq" }
variable "REGISTRY" {
  # Prefix of the image names, for example "ghcr.io/<org>/". Empty: local images.
  default = ""
}

variable "DATABASE_VERSION" { default = "dev" }
variable "BINANCE_BACKFILLER_VERSION" { default = "dev" }
variable "BINANCE_FEEDER_VERSION" { default = "dev" }
variable "BOARD_VERSION" { default = "dev" }

variable "DATABASE_DIR" { default = "../database" }
variable "BINANCE_BACKFILLER_DIR" { default = "../binance-backfiller" }
variable "BINANCE_FEEDER_DIR" { default = "../binance-feeder" }
variable "BOARD_DIR" { default = "../board" }

# Git revisions for the image labels. The Makefile sets them from the
# repositories next to this one; with SOURCE=git the tag is the revision.
variable "DATABASE_REVISION" { default = "" }
variable "BINANCE_BACKFILLER_REVISION" { default = "" }
variable "BINANCE_FEEDER_REVISION" { default = "" }
variable "BOARD_REVISION" { default = "" }

function "src" {
  params = [repo, dir, version]
  result = SOURCE == "git" ? "https://github.com/${GITHUB_ORG}/${repo}.git#${version}" : dir
}

function "image" {
  params = [name, version]
  result = "${REGISTRY}empiriq/${name}:${version}"
}

function "revision" {
  params = [revision, version]
  result = revision != "" ? revision : version
}

group "default" {
  targets = ["database", "binance-backfiller", "binance-feeder", "board"]
}

target "database" {
  context = src("database", DATABASE_DIR, DATABASE_VERSION)
  target  = "database"
  args    = { VERSION = DATABASE_VERSION, REVISION = revision(DATABASE_REVISION, DATABASE_VERSION) }
  tags    = [image("database", DATABASE_VERSION)]
}

target "binance-backfiller" {
  context = src("binance-backfiller", BINANCE_BACKFILLER_DIR, BINANCE_BACKFILLER_VERSION)
  args    = { VERSION = BINANCE_BACKFILLER_VERSION, REVISION = revision(BINANCE_BACKFILLER_REVISION, BINANCE_BACKFILLER_VERSION) }
  tags    = [image("binance-backfiller", BINANCE_BACKFILLER_VERSION)]
}

target "binance-feeder" {
  context = src("binance-feeder", BINANCE_FEEDER_DIR, BINANCE_FEEDER_VERSION)
  args    = { VERSION = BINANCE_FEEDER_VERSION, REVISION = revision(BINANCE_FEEDER_REVISION, BINANCE_FEEDER_VERSION) }
  tags    = [image("binance-feeder", BINANCE_FEEDER_VERSION)]
}

target "board" {
  context = src("board", BOARD_DIR, BOARD_VERSION)
  args    = { VERSION = BOARD_VERSION, REVISION = revision(BOARD_REVISION, BOARD_VERSION) }
  tags    = [image("board", BOARD_VERSION)]
}
