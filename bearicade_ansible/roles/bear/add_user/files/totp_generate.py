#!/usr/bin/env python
import sys
import pyotp
sys.stdout.write(pyotp.random_base32(32))