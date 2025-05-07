#!/bin/bash
# Script to test Cloud Monitoring setup

# Exit on error
set -e

# Check if VM is running
echo "Checking VM status..."
VM_STATUS=$(gcloud compute instances describe lamp-1-vm --format="value(status)")
if [ "$VM_STATUS" != "RUNNING" ]; then
  echo "VM is not running! Current status: $VM_STATUS"
  echo "Starting VM..."
  gcloud compute instances start lamp-1-vm
  echo "Waiting for VM to start..."
  sleep 60
fi

# Get external IP
EXTERNAL_IP=$(gcloud compute instances describe lamp-1-vm --format="value(networkInterfaces[0].accessConfigs[0].natIP)")
echo "VM external IP: $EXTERNAL_IP"

# Test HTTP connection
echo "Testing HTTP connection..."
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://$EXTERNAL_IP/)
if [ $HTTP_STATUS -eq 200 ]; then
  echo "HTTP connection successful (Status code: $HTTP_STATUS)"
else
  echo "HTTP connection failed (Status code: $HTTP_STATUS)"
fi

# Generate some network traffic for alert testing
echo "Generating network traffic to test alerting..."
for i in {1..10}; do
  curl -s http://$EXTERNAL_IP/ > /dev/null
  echo "Request $i sent"
  sleep 1
done

# Check OPS agent status
echo "Checking if OPS agent is installed and running..."
gcloud compute ssh lamp-1-vm --command="sudo systemctl status google-cloud-ops-agent"*" | grep 'Active:'"

echo "Test complete! Check the Cloud Monitoring dashboards and alerts to verify functionality."
echo "It may take a few minutes for metrics to appear in your dashboards."
