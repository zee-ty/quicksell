#!/bin/bash
set -e

# Disable all MPM modules to avoid conflicts
# Remove any enabled MPM symlinks to avoid "More than one MPM loaded" errors
# (some images may have MPMs enabled as symlinks rather than via a2dismod)
for m in mpm_event mpm_worker mpm_prefork; do
	if [ -e "/etc/apache2/mods-enabled/$m.load" ]; then
		rm -f "/etc/apache2/mods-enabled/$m.load"
	fi
	if [ -e "/etc/apache2/mods-enabled/$m.conf" ]; then
		rm -f "/etc/apache2/mods-enabled/$m.conf"
	fi
done

# Enable only mpm_prefork (required for mod_php)
if command -v a2enmod >/dev/null 2>&1; then
	a2enmod mpm_prefork || true
fi

# Start Apache in foreground
exec apache2-foreground