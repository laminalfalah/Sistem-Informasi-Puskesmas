<?php
return $enc = array(
    'home' => array(
        'remote' => sha1('table-home'),
        'sha1' => array(
            sha1('read_home'),
            sha1('search_home'),
            sha1('more_home'),
        )
    ),
    'detail' => array(
        'remote' => sha1('detail-komentar'),
        'sha1' => array(
            sha1('read_komentar'),
            sha1('create_komentar'),
            sha1('store_komentar'),
            sha1('edit_komentar'),
            sha1('update_komentar'),
            sha1('show_komentar'),
            sha1('destroy_komentar'),
        )
    ),
    'export' => array(
        sha1('download'), 
        sha1('pdf'),
        sha1('download_laporan'),
    ),
    'data-dokter' => array(
        'remote' => sha1('table-dokter'),
        'download' => sha1('download_dokter'),
        'unduh' => sha1('unduh_dokter_1'),
        'check' => array(
            sha1('check_dokter'),
            sha1('check_dokter_edit'),
            sha1('check_nip'),
            sha1('check_nip_edit')
        ),
        'sha1' => array(
            sha1('read_dokter'),
            sha1('create_dokter'),
            sha1('store_dokter'),
            sha1('edit_dokter'),
            sha1('update_dokter'),
            sha1('show_dokter'),
            sha1('destroy_dokter'),
            sha1('reset_dokter')
        )
    ),
    'data-pasien' => array(
        'remote' => sha1('table-pasien'),
        'download' => sha1('download_pasien'),
        'unduh' => sha1('unduh_pasien_1'),
        'check' => array(
            sha1('check_pasien'),
            sha1('check_pasien_edit'),
            sha1('check_nik'),
            sha1('check_nik_edit')
        ),
        'sha1' => array(
            sha1('read_pasien'),
            sha1('create_pasien'),
            sha1('store_pasien'),
            sha1('edit_pasien'),
            sha1('update_pasien'),
            sha1('show_pasien'),
            sha1('destroy_pasien'),
            sha1('reset_pasien')
        )
    ),
    'data-staff' => array(
        'remote' => sha1('table-staff'),
        'download' => sha1('download_staff'),
        'unduh' => sha1('unduh_staff_1'),
        'check' => array(
            sha1('check_staff'),
            sha1('check_staff_edit'),
            sha1('check_nik'),
            sha1('check_nik_edit')
        ),
        'sha1' => array(
            sha1('read_staff'),
            sha1('create_staff'),
            sha1('store_staff'),
            sha1('edit_staff'),
            sha1('update_staff'),
            sha1('show_staff'),
            sha1('destroy_staff'),
            sha1('reset_staff')
        )
    ),
    'data-obat' => array(
        'remote' => sha1('table-obat'),
        'download' => sha1('download_obat'),
        'unduh' => sha1('unduh_obat_1'),
        'check' => array(
            sha1('check_obat'),
            sha1('check_obat_edit'),
        ),
        'sha1' => array(
            sha1('read_obat'),
            sha1('create_obat'),
            sha1('store_obat'),
            sha1('edit_obat'),
            sha1('update_obat'),
            sha1('show_obat'),
            sha1('destroy_obat')
        )
    ),
    'data-satuan' => array(
        'remote' => sha1('table-satuan'),
        'check' => array(
            sha1('check_satuan'),
            sha1('check_satuan_edit'),
            sha1('check_kode_u'),
            sha1('check_kode_u_edit')
        ),
        'sha1' => array(
            sha1('read_satuan'),
            sha1('create_satuan'),
            sha1('store_satuan'),
            sha1('edit_satuan'),
            sha1('update_satuan'),
            sha1('show_satuan'),
            sha1('destroy_satuan'),
        )
    ),
    'data-berita' => array(
        'remote' => sha1('table-berita'),
        'sha1' => array(
            sha1('read_berita'),
            sha1('create_berita'),
            sha1('store_berita'),
            sha1('edit_berita'),
            sha1('update_berita'),
            sha1('show_berita'),
            sha1('destroy_berita'),
            sha1('read_komen'),
            sha1('store_komen'),
            sha1('destroy_komen')
        )
    ),
    'data-poli' => array(
        'remote' => sha1('table-poli'),
        'sha1' => array(
            sha1('read_poli'),
            sha1('create_poli'),
            sha1('store_poli'),
            sha1('edit_poli'),
            sha1('update_poli'),
            sha1('show_poli'),
            sha1('destroy_poli'),
        )
    ),
    'data-darah' => array(
        'remote' => sha1('table-darah'),
        'check' => array(
            sha1('check_darah'),
            sha1('check_darah_edit'),
        ),
        'sha1' => array(
            sha1('read_darah'),
            sha1('create_darah'),
            sha1('store_darah'),
            sha1('edit_darah'),
            sha1('update_darah'),
            sha1('show_darah'),
            sha1('destroy_darah'),
        )
    ),
    'data-spesialis' => array(
        'remote' => sha1('table-spesialis'),
        'check' => array(
            sha1('check_spesialis'),
            sha1('check_spesialis_edit'),
            sha1('check_kode_sp'),
            sha1('check_kode_sp_edit')
        ),
        'sha1' => array(
            sha1('read_spesialis'),
            sha1('create_spesialis'),
            sha1('store_spesialis'),
            sha1('edit_spesialis'),
            sha1('update_spesialis'),
            sha1('show_spesialis'),
            sha1('destroy_spesialis'),
        )
    ),
    'data-jadwal' => array(
        'remote' => sha1('table-jadwal'),
        'download' => sha1('download_jadwal'),
        'sha1' => array(
            sha1('read_jadwal'),
            sha1('create_jadwal'),
            sha1('store_jadwal'),
            sha1('edit_jadwal'),
            sha1('update_jadwal'),
            sha1('show_jadwal'),
            sha1('destroy_jadwal'),
            sha1('load_dokternyo'),
        )
    ),
    'data-resep' => array(
        'remote' => sha1('table-resep'),
        'download' => sha1('download_resep'),
        'unduh' => sha1('unduh_resep_1'),
        'sha1' => array(
            sha1('read_resep'),
            sha1('create_resep'),
            sha1('store_resep'),
            sha1('edit_resep'),
            sha1('update_resep'),
            sha1('show_resep'),
            sha1('destroy_resep'),
            sha1('dokter_resep'),
            sha1('pasien_resep'),
            sha1('obat_resep'),
        )
    ),
    'data-rekam_medis' => array(
        'remote' => sha1('table-rawat'),
        'download' => sha1('download_rawat'),
        'unduh' => sha1('unduh_rawat_1'),
        'sha1' => array(
            sha1('read_rawat'),
            sha1('create_rawat'),
            sha1('store_rawat'),
            sha1('edit_rawat'),
            sha1('update_rawat'),
            sha1('show_rawat'),
            sha1('destroy_rawat'),
            sha1('load_pasiennyo'),
            sha1('load_dokternya')
        )
    ),
    'data-rawat' => array(
        'remote' => sha1('table-rekam_medis'),
        'download' => sha1('download_rekam_medis'),
        'unduh' => sha1('unduh_rekam_medis_1'),
        'sha1' => array(
            sha1('read_rekam_medis'),
            sha1('create_rekam_medis'),
            sha1('store_rekam_medis'),
            sha1('edit_rekam_medis'),
            sha1('update_rekam_medis'),
            sha1('show_rekam_medis'),
            sha1('destroy_rekam_medis'),
            sha1('dokter_rekam_medis'),
            sha1('pasien_rekam_medis'),
            sha1('resep_rekam_medis'),
        )
    ),
    'data-beranda' => array(
        'remote' => sha1('table-beranda'),
        'sha1' => array(
            sha1('read_beranda'),
            sha1('create_beranda'),
            sha1('store_beranda'),
            sha1('edit_beranda'),
            sha1('update_beranda'),
            sha1('show_beranda'),
            sha1('destroy_beranda'),
        )
    ),
    'data-profil' => array(
        'remote' => sha1('form_edit'),
        'sha1' => array(
            sha1('edit_profil'),
            sha1('update_profil'),
        )
    ),
    'data-password' => array(
        'remote' => sha1('form_password'),
        'sha1' => array(
            sha1('ubah_password'),
            sha1('check_password')
        )
    ),
);