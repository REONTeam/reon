#!/bin/sh
check_env() {
    value=$(eval echo \$$1)
	if [[ -z "${value}" ]]; then
		echo "$1 not set. Cannot configure from env." 1>&2
		exit 1
	fi
}

if [ ! -f /app/config.json ]; then
	echo "No config found. Building from environment"
	check_env "EXTERNAL_HOSTNAME"
	check_env "EMAIL_DOMAIN"
	check_env "EMAIL_DOMAIN_DION"
	check_env "MYSQL_HOST"
	check_env "MYSQL_USER"
	check_env "MYSQL_PASSWORD"
	check_env "MYSQL_DATABASE"
	
	cat <<- EOF > /app/config.json
	{
	  "hostname": "${EXTERNAL_HOSTNAME}",
	  "email_domain": "${EMAIL_DOMAIN}",
	  "email_domain_dion": "${EMAIL_DOMAIN_DION}",
	  "mysql_host": "${MYSQL_HOST}",
	  "mysql_user": "${MYSQL_USER}",
	  "mysql_password": "${MYSQL_PASSWORD}",
	  "mysql_database": "${MYSQL_DATABASE}"
	}
	EOF
fi

node index.js -c /app/config.json
