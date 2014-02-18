#!/bin/sh
set -e
./test.php
rm -rf docs/*
docco tests/Buttercup/Protects/Tests/*