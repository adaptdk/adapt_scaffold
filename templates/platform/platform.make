; API
api = 2

; Core
core = 7.x

; Drupal project.
; This will download the Drupal core version that the installation profile will
; be built on. This should be updated as newer versions of Drupal get released.
projects[drupal] = {{ drupal_core_version }}

projects[{{ profile }}][type] = profile
projects[{{ profile }}][download][type] = git
projects[{{ profile }}][download][url] = {{ gituri }}/{{ profile }}.git

