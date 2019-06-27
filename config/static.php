<?php

return $static = array(
    'data-dokter' => array(
        'permissions' => array(
            'create_dokter',
            'search_user'
        ),
        'box-create' => array(
            'box-add' => array(
                'id' => 'create_dokter',
                'name' => 'create_dokter',
                'class' => 'btn btn-primary btn-sm',
                'title' => 'Tambah Data Dokter',
                'data-target' => base64_encode($enc['data-dokter']['sha1'][1])
            ),
            'box-download' => array(
                'id' => 'download_dokter',
                'name' => 'download_dokter',
                'class' => 'btn btn-default btn-sm',
                'title' => 'Export ke *.pdf',
                'data-remote' => base64_encode($enc['export'][0]),
                'data-target' => base64_encode($enc['export'][1])
            )
        ),
        'table' => array(
            'id' => 'table_dokter',
            'name' => 'table_dokter',
            'class' => 'table table-bordered table-striped table-hover table_dokter',
            'data-remote' => base64_encode($enc['data-dokter']['remote']),
            'data-target' => base64_encode($enc['data-dokter']['sha1'][0]),
            'field' => array('No.','NIP','Nama','Nama Pengguna','Jenis Kelamin','Status','Aksi')
        ),
    ),
    'data-pasien' => array(
        'permissions' => array(
            'create_pasien',
            'search_user'
        ),
        'box-create' => array(
            'box-add' => array(
                'id' => 'create_pasien',
                'name' => 'create_pasien',
                'class' => 'btn btn-primary btn-sm',
                'title' => 'Tambah Data Pasien',
                'data-target' => base64_encode($enc['data-pasien']['sha1'][1])
            ),
            'box-download' => array(
                'id' => 'download_pasien',
                'name' => 'download_pasien',
                'class' => 'btn btn-default btn-sm',
                'title' => 'Export ke *.pdf',
                'data-remote' => base64_encode($enc['export'][0]),
                'data-target' => base64_encode($enc['export'][1])
            )
        ),
        'table' => array(
            'id' => 'table_pasien',
            'name' => 'table_pasien',
            'class' => 'table table-bordered table-striped table-hover table_pasien',
            'data-remote' => base64_encode($enc['data-pasien']['remote']),
            'data-target' => base64_encode($enc['data-pasien']['sha1'][0]),
            'field' => array('No.','NIK','Nama','Nama Pengguna','Jenis Kelamin','Status','Aksi')
        ),
    ),
    'data-staff' => array(
        'permissions' => array(
            'create_staff',
            'search_user'
        ),
        'box-create' => array(
            'box-add' => array(
                'id' => 'create_staff',
                'name' => 'create_staff',
                'class' => 'btn btn-primary btn-sm',
                'title' => 'Tambah Data Staff',
                'data-target' => base64_encode($enc['data-staff']['sha1'][1])
            ),
            'box-download' => array(
                'id' => 'download_staff',
                'name' => 'download_staff',
                'class' => 'btn btn-default btn-sm',
                'title' => 'Export ke *.pdf',
                'data-remote' => base64_encode($enc['export'][0]),
                'data-target' => base64_encode($enc['export'][1])
            )
        ),
        'table' => array(
            'id' => 'table_staff',
            'name' => 'table_staff',
            'class' => 'table table-bordered table-striped table-hover table_staff',
            'data-remote' => base64_encode($enc['data-staff']['remote']),
            'data-target' => base64_encode($enc['data-staff']['sha1'][0]),
            'field' => array('No.','NIP','Nama','Nama Pengguna','Jenis Kelamin','Status','Aksi')
        ),
    ),
    'data-obat' => array(
        'permissions' => array(
            'create_obat',
            'search_user'
        ),
        'box-create' => array(
            'box-add' => array(
                'id' => 'create_obat',
                'name' => 'create_obat',
                'class' => 'btn btn-primary btn-sm',
                'title' => 'Tambah Data Obat',
                'data-target' => base64_encode($enc['data-obat']['sha1'][1])
            ),
            'box-download' => array(
                'id' => 'download_obat',
                'name' => 'download_obat',
                'class' => 'btn btn-default btn-sm',
                'title' => 'Export ke *.pdf',
                'data-remote' => base64_encode($enc['export'][0]),
                'data-target' => base64_encode($enc['export'][1])
            )
        ),
        'table' => array(
            'id' => 'table_obat',
            'name' => 'table_obat',
            'class' => 'table table-bordered table-striped table-hover table_obat',
            'data-remote' => base64_encode($enc['data-obat']['remote']),
            'data-target' => base64_encode($enc['data-obat']['sha1'][0]),
            'field' => array('No.','Kode Obat','Nama Obat','Stok','Harga Beli','Harga Jual','Aksi')
        ),
    ),
    'data-satuan' => array(
        'permissions' => array(
            'create_satuan',
            'search_satuan'
        ),
        'box-create' => array(
            'box-add' => array(
                'id' => 'create_satuan',
                'name' => 'create_satuan',
                'class' => 'btn btn-primary',
                'title' => 'Tambah Data Satuan',
                'data-target' => base64_encode($enc['data-satuan']['sha1'][1])
            ),
        ),
        'table' => array(
            'id' => 'table_satuan',
            'name' => 'table_satuan',
            'class' => 'table table-bordered table-striped table-hover table_satuan',
            'data-remote' => base64_encode($enc['data-satuan']['remote']),
            'data-target' => base64_encode($enc['data-satuan']['sha1'][0]),
            'field' => array('No.','Nama Satuan','Aksi')
        )
    ),
    'data-berita' => array(
        'permissions' => array(
            'create_berita',
            'search_user'
        ),
        'box-create' => array(
            'box-add' => array(
                'id' => 'create_berita',
                'name' => 'create_berita',
                'class' => 'btn btn-primary btn-sm',
                'title' => 'Tambah Data Berita',
                'data-target' => base64_encode($enc['data-berita']['sha1'][1])
            ),
        ),
        'table' => array(
            'id' => 'table_berita',
            'name' => 'table_berita',
            'class' => 'table table-bordered table-striped table-hover table_berita',
            'data-remote' => base64_encode($enc['data-berita']['remote']),
            'data-target' => base64_encode($enc['data-berita']['sha1'][0]),
            'field' => array('No.','Judul Berita','Status','Aksi')
        ),
    ),
    'data-poli' => array(
        'permissions' => array(
            'create_poli',
            'search_user'
        ),
        'box-create' => array(
            'box-add' => array(
                'id' => 'create_poli',
                'name' => 'create_poli',
                'class' => 'btn btn-primary btn-sm',
                'title' => 'Tambah Data Poli',
                'data-target' => base64_encode($enc['data-poli']['sha1'][1])
            ),
        ),
        'table' => array(
            'id' => 'table_poli',
            'name' => 'table_poli',
            'class' => 'table table-bordered table-striped table-hover table_poli',
            'data-remote' => base64_encode($enc['data-poli']['remote']),
            'data-target' => base64_encode($enc['data-poli']['sha1'][0]),
            'field' => array('No.','Nama Poli','Aksi')
        ),
    ),
    'data-darah' => array(
        'permissions' => array(
            'create_darah',
            'search_satuan'
        ),
        'box-create' => array(
            'box-add' => array(
                'id' => 'create_darah',
                'name' => 'create_darah',
                'class' => 'btn btn-primary',
                'title' => 'Tambah Data Darah',
                'data-target' => base64_encode($enc['data-darah']['sha1'][1])
            ),
        ),
        'table' => array(
            'id' => 'table_darah',
            'name' => 'table_darah',
            'class' => 'table table-bordered table-striped table-hover table_darah',
            'data-remote' => base64_encode($enc['data-darah']['remote']),
            'data-target' => base64_encode($enc['data-darah']['sha1'][0]),
            'field' => array('No.','Golongan Darah','Aksi')
        )
    ),
    'data-spesialis' => array(
        'permissions' => array(
            'create_spesialis',
            'search_user'
        ),
        'box-create' => array(
            'box-add' => array(
                'id' => 'create_spesialis',
                'name' => 'create_spesialis',
                'class' => 'btn btn-primary',
                'title' => 'Tambah Data Spesialis',
                'data-target' => base64_encode($enc['data-spesialis']['sha1'][1])
            ),
        ),
        'table' => array(
            'id' => 'table_spesialis',
            'name' => 'table_spesialis',
            'class' => 'table table-bordered table-striped table-hover table_spesialis',
            'data-remote' => base64_encode($enc['data-spesialis']['remote']),
            'data-target' => base64_encode($enc['data-spesialis']['sha1'][0]),
            'field' => array('No.','Kode Spesialis','Nama Spesialis','Aksi')
        )
    ),
    'data-jadwal' => array(
        'permissions' => array(
            'create_jadwal',
            'search_user'
        ),
        'box-create' => array(
            'box-add' => array(
                'id' => 'create_jadwal',
                'name' => 'create_jadwal',
                'class' => 'btn btn-primary btn-sm',
                'title' => 'Tambah Data Jadwal',
                'data-target' => base64_encode($enc['data-jadwal']['sha1'][1])
            ),
            'box-download' => array(
                'id' => 'download_jadwal',
                'name' => 'download_jadwal',
                'class' => 'btn btn-default btn-sm',
                'title' => 'Export ke *.pdf',
                'data-remote' => base64_encode($enc['export'][0]),
                'data-target' => base64_encode($enc['export'][1])
            )
        ),
        'table' => array(
            'id' => 'table_jadwal',
            'name' => 'table_jadwal',
            'class' => 'table table-bordered table-striped table-hover table_jadwal',
            'data-remote' => base64_encode($enc['data-jadwal']['remote']),
            'data-target' => base64_encode($enc['data-jadwal']['sha1'][0]),
            'field' => array('No.','Nama Poli','Nama Dokter','Jadwal','Aksi')
        ),
    ),
    'data-resep' => array(
        'permissions' => array(
            'create_resep',
            'search_user'
        ),
        'box-create' => array(
            'box-add' => array(
                'id' => 'create_resep',
                'name' => 'create_resep',
                'class' => 'btn btn-primary btn-sm',
                'title' => 'Tambah Data Resep',
                'data-target' => base64_encode($enc['data-resep']['sha1'][1])
            ),
            'box-download' => array(
                'id' => 'download_resep',
                'name' => 'download_resep',
                'class' => 'btn btn-default btn-sm',
                'title' => 'Export ke *.pdf',
                'data-remote' => base64_encode($enc['export'][0]),
                'data-target' => base64_encode($enc['export'][1])
            )
        ),
        'table' => array(
            'id' => 'table_resep',
            'name' => 'table_resep',
            'class' => 'table table-bordered table-striped table-hover table_resep',
            'data-remote' => base64_encode($enc['data-resep']['remote']),
            'data-target' => base64_encode($enc['data-resep']['sha1'][0]),
            'field' => array('No.','Kode Resep','Nama Dokter','Nama Pasien','Tanggal','Aksi')
        ),
    ),
    'data-rekam_medis' => array(
        'permissions' => array(
            'create_rekam_medis',
            'search_user'
        ),
        'box-create' => array(
            'box-add' => array(
                'id' => 'create_rekam_medis',
                'name' => 'create_rekam_medis',
                'class' => 'btn btn-primary btn-sm',
                'title' => 'Tambah Data Rekam Medis',
                'data-target' => base64_encode($enc['data-rekam_medis']['sha1'][1])
            ),
            'box-download' => array(
                'id' => 'download_rekam_medis',
                'name' => 'download_rekam_medis',
                'class' => 'btn btn-default btn-sm',
                'title' => 'Export ke *.pdf',
                'data-remote' => base64_encode($enc['export'][0]),
                'data-target' => base64_encode($enc['export'][1])
            )
        ),
        'table' => array(
            'id' => 'table_rekam_medis',
            'name' => 'table_rekam_medis',
            'class' => 'table table-bordered table-striped table-hover table_rekam_medis',
            'data-remote' => base64_encode($enc['data-rekam_medis']['remote']),
            'data-target' => base64_encode($enc['data-rekam_medis']['sha1'][0]),
            'field' => array('No.','Nama Pasien','Nama Dokter','Tanggal','Aksi')
        ),
    ),
    'data-rawat' => array(
        'permissions' => array(
            'create_rawat_jalan',
            'search_user'
        ),
        'box-create' => array(
            'box-add' => array(
                'id' => 'create_rawat_jalan',
                'name' => 'create_rawat_jalan',
                'class' => 'btn btn-primary btn-sm',
                'title' => 'Tambah Data Rawat Jalan',
                'data-target' => base64_encode($enc['data-rawat']['sha1'][1])
            ),
            'box-download' => array(
                'id' => 'download_rawat_jalan',
                'name' => 'download_rawat_jalan',
                'class' => 'btn btn-default btn-sm',
                'title' => 'Export ke *.pdf',
                'data-remote' => base64_encode($enc['export'][0]),
                'data-target' => base64_encode($enc['export'][1])
            )
        ),
        'table' => array(
            'id' => 'table_rawat_jalan',
            'name' => 'table_rawat_jalan',
            'class' => 'table table-bordered table-striped table-hover table_rawat_jalan',
            'data-remote' => base64_encode($enc['data-rawat']['remote']),
            'data-target' => base64_encode($enc['data-rawat']['sha1'][0]),
            'field' => array('No.','Nama Pasien','Nama Dokter','Kode Resep','Tanggal','Aksi')
        ),
    ),
    'data-beranda' => array(
        'permissions' => array(
            'create_sistem',
            'search_user'
        ),
        'box-create' => array(
            'box-add' => array(
                'id' => 'create_beranda',
                'name' => 'create_beranda',
                'class' => 'btn btn-primary btn-sm',
                'title' => 'Tambah Data Menu',
                'data-target' => base64_encode($enc['data-beranda']['sha1'][1])
            ),
        ),
        'table' => array(
            'id' => 'table_beranda',
            'name' => 'table_beranda',
            'class' => 'table table-bordered table-striped table-hover table_beranda',
            'data-remote' => base64_encode($enc['data-beranda']['remote']),
            'data-target' => base64_encode($enc['data-beranda']['sha1'][0]),
            'field' => array('No.','Menu','Aksi')
        ),
    ),
);