api = 2
core = 7.x

{% for project in projects %}
projects[{{ project.name }}][type] = {{ project.type }}
projects[{{ project.name }}][version] = {{ project.version }}
{% if project.download %}
projects[{{ project.name }}][download][type] = {{ project.download.type }}
projects[{{ project.name }}][download][branch] = {{ project.download.branch }}
{% endif %}
{% if project.subdir %}
projects[{{ project.name }}][subdir] = {{ project.subdir }}
{% endif %}

{% endfor %}

; Adapt core
projects[adapt_core][type] = 'module'
projects[adapt_core][subdir] = 'global'
projects[adapt_core][download][type] = 'git'
projects[adapt_core][download][url] = 'http://github.com/adaptdk/adapt_core.git'
projects[adapt_core][download][tag] = '0.6'

; Adapt Media
projects[adapt_media][type] = 'module'
projects[adapt_media][subdir] = 'global'
projects[adapt_media][download][type] = 'git'
projects[adapt_media][download][url] = 'http://github.com/adaptdk/adapt_media.git'
projects[adapt_media][download][tag] = '0.2'

; Adapt Wysiwyg
projects[adapt_wysiwyg][type] = 'module'
projects[adapt_wysiwyg][subdir] = 'global'
projects[adapt_wysiwyg][download][type] = 'git'
projects[adapt_wysiwyg][download][url] = 'http://github.com/adaptdk/adapt_wysiwyg.git'
projects[adapt_wysiwyg][download][tag] = '0.1'

; Adapt Basetheme
projects[adapt_basetheme][type] = 'theme'
projects[adapt_basetheme][subdir] = 'global'
projects[adapt_basetheme][download][type] = 'git'
projects[adapt_basetheme][download][url] = 'http://github.com/adaptdk/adapt_basetheme.git'
projects[adapt_basetheme][download][tag] = '0.4'
