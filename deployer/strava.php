<?php

namespace Deployer;

desc('Register Strava webhook');
task('artisan:strava:webhook', artisan('strava:webhook'));
