.PHONY: dev stop restart

dev:
	symfony server:start --no-tls

stop:
	symfony server:stop

restart:
	symfony server:stop || true
	symfony server:start --no-tls

