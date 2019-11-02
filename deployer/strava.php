<?php

namespace Deployer;

desc('Register strava webhook');
task('artisan:strava:webhook', artisan('strava:webhook'));
