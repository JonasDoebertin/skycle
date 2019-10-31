<?php

namespace Deployer;

desc('Publish Telescope assets');
task('artisan:telescope:assets', artisan('telescope:publish'));
