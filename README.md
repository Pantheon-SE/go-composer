[![License](https://img.shields.io/packagist/l/pantheon-se/go-composer)](LICENSE) [![Packagist Version](https://img.shields.io/packagist/v/pantheon-se/go-composer)](https://packagist.org/packages/pantheon-se/go-composer) [![Tests](https://github.com/pantheon-se/go-composer/actions/workflows/test.yml/badge.svg?branch=2.x)](https://github.com/Pantheon-SE/go-composer/actions)

# Go Composer

> Composer Plugin to install other utilities via Composer with Go.

Based on [node-composer by mariusbuescher](https://github.com/mariusbuescher/node-composer), this Composer plugin will install a version of Go into your vendor/bin directory so that they are available to use during your Composer builds. This plugin helps automate the download of the binaries which are linked to the bin-directory specified in your composer.json.

Once installed, you can then use Go in your composer-scripts.

## Setup

Simply install the plugin, and the latest Go will be installed - **no other configurations are necessary**. Optionally, you can specify the `go-version` in your composer.json extra configs to declare a specific version of Go.

**Example composer.json**

```json
{
  "name": "my/project",
  "type": "project",
  "license": "MIT",
  "require": {
    "pantheon-se/go-composer": "*"
  },
  "extra": {
    "pantheon-se": {
      "go-composer": {
        "go-version": true
      }
    }
  },
  "config": {
    "allow-plugins": {
      "pantheon-se/go-composer": true
    }
  }
}
```

## Configuration

There are three parameters you can configure: 
- Go version (`go-version`)
- The download url template for the Go binary archives (`go-download-url`).

In the Node download url, replace the following placeholders with your specific needs:

- version: `${version}`
- type of your os: `${osType}`
- system architecture: `${architecture}`
- file format `${format}`

**Example composer.json with specific versions of Go** 

```json
{
  "extra": {
    "pantheon-se": {
      "go-composer": {
        "go-version": "1.20.2",
        "go-download-url": "https://go.dev/dl/go${version}.${osType}-${architecture}.${format}"
      }
    }
  }
}
```
