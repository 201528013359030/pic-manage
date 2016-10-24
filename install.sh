#!/bin/bash
set -o nounset
set -o errexit
INSTALL_DIR=/usr/local/www/nginx/html/appframe
mkdir -p $INSTALL_DIR

\cp  -rf ./* $INSTALL_DIR
chmod 777 $INSTALL_DIR/web  -R
chmod 777 $INSTALL_DIR/runtime -R
chmod 777 $INSTALL_DIR/mail -R

