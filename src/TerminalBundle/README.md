Terminal Bundle
===

## Connecting a Client

You can connect to the Terminal server using standard UNIX tools.  
Below are two recommended methods depending on your system and preferences.

### 1. Using rlwrap with nc (recommended)

```shell
rlwrap nc 127.0.0.1 2009
```
* `nc` connects to the TCP server.
* `rlwrap` provides command history, arrow keys, and line editing support.

### 2. Using socat (alternative)

```shell
socat -,raw,echo=0 tcp:127.0.0.1:2009
```
* `socat` connects to the TCP server and handles raw input.
* You can use it if `rlwrap` is not available.

### Notes

* Replace `127.0.0.1` and `2009` with the actual server address and port.
* `rlwrap` is more user-friendly as it supports history and arrow keys.
* On most Linux and macOS systems you can install `rlwrap` and `socat` via your package manager:
```shell
apt-get install rlwrap socat     # Debian/Ubuntu
brew install rlwrap socat        # macOS with Homebrew
```