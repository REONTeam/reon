#!/bin/sh

if [[ -z "${EXTERNAL_IP}" ]]; then
	>&2 echo "ERROR: Environment variable EXTERNAL_IP must be set"
	exit 1
fi

cat >/etc/dnsmasq.conf <<EOF
no-resolv
no-hosts
address=/*.dion.ne.jp/${EXTERNAL_IP}
address=/gameboy.datacenter.ne.jp/${EXTERNAL_IP}
EOF

# Don't fork, log queries, log to stdout
dnsmasq -d -q --log-facility=-
