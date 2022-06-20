# What is this project?
This is Spork. A multipurpose megatool that exposes a certain kind of workflow to enable quickly putting together the UI and API of a project.

### What is the indended use?
IDK about you, but I intend on using this app as the test bed for all my new ideas. Anything I want to track and build a website for, without creating a whole new Laravel project with a whole extra place to login. 

I intend on supporting this passively, so it may go many months without an update, then see a flury of updates, before going quiet again.

Most of my focus will be based around Spork plugins, because that'll be the workflow.
 - Have new idea
 - Make new spork plugin
 - Save lots of time
 - Spend more time writing code
 
## What does this project offer preconfigured?
 - SPA + Authentication via Vue3 + Vuex
 - 2FA through Laravel Fortify
 - Activity Tracking through Spatie's Activity Logger
 - A dynamic crud api that just needs a model and a few fields defined
 - Laravel Scout preconfigured
 - Self-hosted php-based websocket server replacement via Beyoncode's Laravel Websockets package
 - A dedicated queue worker container running Laravel
 - A dedicated crontab container
 - Belongs To Through relationship support via staudenmeir/belongs-to-through
 - Model tagging via spatie/laravel-tags
 - Dynamic feature handling/loading


## How can I setup Spork to use as a template for my stuff?
Honestly, I'd recommend cloning the repo via git or downloading the zipfile.

```
git clone git@github.com:spork-app/app
git remote rm origin
```

From there, you can use the install script

```
bash install.sh
```

You'll be prompted for installing available packages with the `spork-app` tag through composer.

Any custom packages can be added to Spork via [custom repositories through composer](https://getcomposer.org/doc/05-repositories.md#vcs).

And plugin apps can be included directly in your `resources/js/app.js` file via `require('@vendor/phppackage/name/path/to/resources/app')`

## How can I make my own plugin?
There's a package for that :smile: https://github.com/spork-app/template-plugin. It's templated with the keys of [the spork.json file](https://github.com/spork-app/template-plugin/blob/main/spork.json), and it's intended to be used with the spork/development package, which will automatically prompt for and replace values in the `spork.json` file.