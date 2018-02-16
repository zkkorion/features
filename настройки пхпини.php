Settings

	apache2/php.ini and cli/php.ini
	mbstring.internal_encoding = UTF-8
	mbstring.func_overload = 2 //значение "0" для допступа в phpMyAdmin 
	realpath_cache_size = 4096k
	display_errors = On
	short_open_tag=On
	post_max_size = 256M
	upload_max_filesize = 256M
	max_file_uploads = 30
	default_socket_timeout = 120
	date.timezone = Europe/Moscow
conf.d/apc.ini
	apc.enabled = On
	apc.cache_by_default = On
	apc.num_files_hint = 20000
	apc.user_entries_hint = 20000
	apc.ttl = 86400
	apc.max_file_size = 4M
	apc.stat = On 
	apc.shm_size = 128M