#!/bin/bash
sudo cp -rf ./systemd/*.service /etc/systemd/system/
sudo systemctl daemon-reload
