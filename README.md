# Google Cloud Monitoring

This repository contains a complete guide for implementing cloud monitoring on a LAMP (Linux, Apache, MySQL, PHP) stack running on Google Cloud Platform (GCP).

## Overview

Cloud Monitoring provides visibility into the performance, uptime, and overall health of cloud-powered applications. This project demonstrates how to:

- Set up a Compute Engine VM instance with Apache and PHP
- Install monitoring and logging agents
- Create uptime checks
- Configure alerting policies
- Build custom dashboards
- View and analyze logs

## Video

https://youtu.be/ev7pXMxI6h8

## Prerequisites

- Google Cloud Platform account
- Basic knowledge of Linux commands
- Basic understanding of web servers

## Implementation Steps

### 1. Create a Compute Engine Instance

```bash
# Set your region and zone
gcloud config set compute/zone "YOUR_ZONE"
export ZONE=$(gcloud config get compute/zone)

gcloud config set compute/region "YOUR_REGION"
export REGION=$(gcloud config get compute/region)

# Create VM instance via gcloud (alternatively use the Console UI)
gcloud compute instances create lamp-1-vm \
  --zone=$ZONE \
  --machine-type=e2-medium \
  --image-family=debian-12 \
  --image-project=debian-cloud \
  --tags=http-server \
  --boot-disk-size=10GB
  
# Create firewall rule to allow HTTP traffic
gcloud compute firewall-rules create allow-http \
  --direction=INGRESS \
  --priority=1000 \
  --network=default \
  --action=ALLOW \
  --rules=tcp:80 \
  --source-ranges=0.0.0.0/0 \
  --target-tags=http-server
```

### 2. Install and Configure Apache and PHP

SSH into your VM instance and run the following commands:

```bash
sudo apt-get update
sudo apt-get install apache2 php7.0 -y
sudo service apache2 restart
```

Note: If you cannot install php7.0, use php5 instead.

### 3. Set Up Monitoring Metrics Scope

In the Google Cloud Console:
1. Navigate to Monitoring (Observability > Monitoring)
2. The system will automatically create a metrics scope for your project

### 4. Install Monitoring and Logging Agents

```bash
# Install the Operations (formerly Stackdriver) agent
curl -sSO https://dl.google.com/cloudagents/add-google-cloud-ops-agent-repo.sh
sudo bash add-google-cloud-ops-agent-repo.sh --also-install

# Verify the agent is running
sudo systemctl status google-cloud-ops-agent"*"

# Make sure your system is up to date
sudo apt-get update
```

### 5. Create an Uptime Check

In the Cloud Console:
1. Navigate to Monitoring > Uptime Checks
2. Click "Create Uptime Check"
3. Configure as follows:
   - Protocol: HTTP
   - Resource Type: Instance
   - Instance: lamp-1-vm
   - Check Frequency: 1 minute
   - Title: Lamp Uptime Check
4. Test and create the check

### 6. Create an Alerting Policy

In the Cloud Console:
1. Navigate to Monitoring > Alerting
2. Click "Create Policy"
3. Select the metric: VM instance > Interface > Network traffic
4. Configure threshold: Above 500 with 1 min retest window
5. Configure notification channel (email)
6. Provide documentation and name the alert "Inbound Traffic Alert"

### 7. Create a Custom Dashboard

In the Cloud Console:
1. Navigate to Monitoring > Dashboards
2. Create custom dashboard named "Cloud Monitoring LAMP Qwik Start Dashboard"
3. Add CPU Load widget:
   - Visualization: Line
   - Metric: VM instance > CPU > CPU load (1m)
4. Add Received Packets widget:
   - Visualization: Line
   - Metric: VM instance > Instance > Received packets

### 8. View Logs

In the Cloud Console:
1. Navigate to Logging > Logs Explorer
2. Filter logs for your VM instance
3. Monitor log events, especially during VM state changes (start/stop)

## Testing and Verification

1. View your Apache default page by visiting your VM's external IP
2. Monitor uptime checks to ensure they're reporting correctly
3. Test alerting by generating traffic or stopping/starting your VM
4. Check your custom dashboard to view collected metrics

## Resources

- [Cloud Monitoring Documentation](https://cloud.google.com/monitoring/docs)
- [Cloud Logging Documentation](https://cloud.google.com/logging/docs)
- [Compute Engine Documentation](https://cloud.google.com/compute/docs)

## License

This project is licensed under the MIT License - see the LICENSE file for details.
