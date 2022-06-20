# What is this project?
This is Spork. A multipurpose megatool that exposes a certain kind of workflow to enable quickly putting together the UI and API of a project.

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

And assets can be included directly in your `resources/js/app.js` file

## How can I make my own plugin?
There's a package for that :smile: https://github.com/spork-app/template-plugin. It's templated with the keys of [the spork.json file](https://github.com/spork-app/template-plugin/blob/main/spork.json), and it's intended to be used with the spork/development package, which will automatically prompt for and replace values in the `spork.json` file.