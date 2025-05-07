#!/bin/bash
# Script to install LAMP stack and Cloud Monitoring agents on Google Cloud VM

# Exit on error
set -e

# Update package lists
echo "Updating package lists..."
sudo apt-get update

# Install Apache and PHP
echo "Installing Apache and PHP..."
sudo apt-get install -y apache2 php7.0
# Fallback to PHP5 if PHP7.0 is not available
if [ $? -ne 0 ]; then
    echo "PHP 7.0 not available, falling back to PHP5..."
    sudo apt-get install -y apache2 php5
fi

# Restart Apache to apply changes
echo "Restarting Apache..."
sudo service apache2 restart

# Install the Google Cloud Ops Agent
echo "Installing Google Cloud Ops Agent..."
curl -sSO https://dl.google.com/cloudagents/add-google-cloud-ops-agent-repo.sh
sudo bash add-google-cloud-ops-agent-repo.sh --also-install

# Check agent status
echo "Checking Google Cloud Ops Agent status..."
sudo systemctl status google-cloud-ops-agent"*"

echo "LAMP stack and monitoring agents installed successfully!"
echo "You can now access the default Apache page at http://[YOUR_VM_EXTERNAL_IP]/"
echo "Cloud Monitoring is now collecting metrics from your VM."
