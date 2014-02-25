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
projects[adapt_core][download][tag] = '0.7'

; Adapt Basetheme
projects[adapt_basetheme][type] = 'theme'
projects[adapt_basetheme][subdir] = 'global'
projects[adapt_basetheme][download][type] = 'git'
projects[adapt_basetheme][download][url] = 'http://github.com/adaptdk/adapt_basetheme.git'
projects[adapt_basetheme][download][tag] = '0.4'
