<?php



return [
	
	'user-management' => [
		'title' => 'User Management',
		'created_at' => 'Time',
		'fields' => [
		]
	],
	
	'permissions' => [
		'title' => 'Permissions',
		'created_at' => 'Time',
		'fields' => [
			'name' => 'Name'
		]
	],
	
	'roles' => [
		'title' => 'Roles',
		'created_at' => 'Time',
		'fields' => [
			'name' => 'Name',
			'permission' => 'Permissions'
		]
	],
	
	'users' => [
		'title' => 'Users',
		'created_at' => 'Time',
		'fields' => [
			'name' => 'Name',
			'email' => 'Email',
			'password' => 'Password',
			'roles' => 'Roles',
			'remember-token' => 'Remember token'
		]
	],

	'global_title' => 'Aset Management SS',
	'app_create' => 'Buat Baru',
	'app_save' => 'Simpan',
	'app_view' => 'Tampil',
	'app_list' => 'List Data',
	'app_no_entries_in_table' => 'Tidak Ada Data',
	'app_logout' => 'Keluar',
	'app_dashboard' => 'Dashboard',
	'app_delete' => 'Hapus',
	'app_deleteselected' => 'Hapus Terpilih',
	'app_edit' => 'Edit',
	'app_update' => 'Update',
	'app_back' => 'Kembali',
	'app_close' => 'Tutup',
	'app_selectall' => 'Pilih Semua',
	'app_selectnone' => 'Tidak Pilih Semua',
	'app_confirm' => 'Apakah yakin data sudah benar ?',
	'app_search' => 'Pencarian',
];