moodle-quizarchive_campla
=========================
[![Moodle Plugin CI](https://github.com/lucaboesch/moodle-quizaccess_campla/actions/workflows/moodle-plugin-ci.yml/badge.svg?branch=main)](https://github.com/lucaboesch/moodle-quizaccess_campla/actions/workflows/moodle-plugin-ci.yml)
[![Latest Release](https://img.shields.io/github/v/release/lucaboesch/moodle-quizaccess_campla?sort=semver&color=orange)](https://github.com/lucaboesch/moodle-quizaccess_campla/releases)
[![PHP Support](https://img.shields.io/badge/php-8.1--8.4-blue)](https://github.com/lucaboesch/moodle-quizaccess_campla/actions)
[![Moodle Support](https://img.shields.io/badge/Moodle-4.4--5.2+-orange)](https://github.com/lucaboesch/moodle-quizaccess_campla/actions)
[![License GPL-3.0](https://img.shields.io/github/license/lucaboesch/moodle-quizaccess_campla?color=lightgrey)](https://github.com/lucaboesch/moodle-quizaccess_campla/blob/main/LICENSE)
[![GitHub contributors](https://img.shields.io/github/contributors/lucaboesch/moodle-quizaccess_campla)](https://github.com/lucaboesch/moodle-quizaccess_campla/graphs/contributors)

Moodle quiz access plugin to allow for easy configuration of CAMPLA exams.


Requirements
------------

This plugin requires Moodle 4.4 onwards.<br/>
This plugin requires a CAMPLA setup [campla.ch](https://campla.ch/?lang=en).

Installation
------------

Install the plugin to folder
/mod/quiz/accessrule/campla

See http://docs.moodle.org/en/Installing_plugins for details on installing Moodle plugins

Configuration
-------------

CAMPLA is an infrastructure service that institutions do self host.<br/>
It is then their duty to have the credentials and secrets set up and transferred to Moodle
in order to get the correct configuration up and running to allow this quiz settings button
to work properly.<br/>
More information on how to set up CAMPLA can be found on [campla.ch](https://campla.ch/?lang=en).