#!/bin/bash
set -eux

dnf update -y

dnf install docker -y

systemctl enable docker
systemctl start docker

usermod -aG docker ec2-user