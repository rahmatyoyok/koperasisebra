<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'Field :attribute harus disetujui.',
    'active_url'           => 'Field :attribute bukan URL yang aktif.',
    'after'                => 'Field :attribute harus sebuah tanggal setelah :date.',
    'after_or_equal'       => 'Field :attribute harus sebuah tanggal setelah atau sama dengan :date.',
    'alpha'                => 'Field :attribute hanya diperbolehkan huruf.',
    'alpha_dash'           => 'Field :attribute hanya diperbolehkan huruf, angka, dan -.',
    'alpha_num'            => 'Field :attribute hanya diperbolehkan huruf dan angka.',
    'array'                => 'Field :attribute harus sebuah array.',
    'before'               => 'Field :attribute harus sebuah tanggal sebelum :date.',
    'before_or_equal'      => 'Field :attribute harus sebuah tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'numeric' => 'Field :attribute harus diantara :min dan :max.',
        'file'    => 'Field :attribute harus diantara :min dan :max kilobytes.',
        'string'  => 'Field :attribute harus diantara :min dan :max karakter.',
        'array'   => 'Field :attribute harus diantara :min dan :max item.',
    ],
    'boolean'              => 'Field :attribute harus true atau false.',
    'confirmed'            => 'Field :attribute tidak cocok.',
    'captcha'              => 'Captcha salah.',
    'date'                 => 'Field :attribute harus tanggal.',
    'date_format'          => 'Field :attribute tidak cocok dengan format :format.',
    'different'            => 'Field :attribute dan :other harus berbeda.',
    'digits'               => 'Field :attribute harus :digits digit.',
    'digits_between'       => 'Field :attribute harus diantara :min dan :max digit.',
    'dimensions'           => 'Field :attribute harus sebuah dimensi gambar.',
    'distinct'             => 'Field :attribute tidak boleh memiliki isi yang sama.',
    'email'                => 'Field :attribute harus sebuah alamat email.',
    'exists'               => 'Field :attribute yang dipilih tidak valid.',
    'file'                 => 'Field :attribute harus sebuah file.',
    'filled'               => 'Field :attribute harus memiliki nilai.',
    'image'                => 'Field :attribute harus sebuah gambar.',
    'in'                   => 'Field :attribute yang dipilih tidak valid.',
    'in_array'             => 'Field :attribute tidak ada pada :other.',
    'integer'              => 'Field :attribute harus sebuah integer.',
    'ip'                   => 'Field :attribute harus sebuah alamat IP.',
    'ipv4'                 => 'Field :attribute harus sebuah alamat IPv4.',
    'ipv6'                 => 'Field :attribute harus sebuah alamat IPv6.',
    'json'                 => 'Field :attribute harus sebuah string JSON.',
    'max'                  => [
        'numeric' => 'Field :attribute tidak boleh lebih besar dari :max.',
        'file'    => 'Field :attribute tidak boleh lebih besar dari :max kilobytes.',
        'string'  => 'Field :attribute tidak boleh lebih besar dari :max karakter.',
        'array'   => 'Field :attribute maksimal hanya boleh :max item.',
    ],
    'mimes'                => 'Field :attribute harus sebuah file bertipe: :values.',
    'mimetypes'            => 'Field :attribute harus sebuah file bertipe: :values.',
    'min'                  => [
        'numeric' => 'Field :attribute minimal harus :min.',
        'file'    => 'Field :attribute minimal harus :min kilobytes.',
        'string'  => 'Field :attribute minimal harus :min karakter.',
        'array'   => 'Field :attribute minimal harus ada :min item.',
    ],
    'not_in'               => 'Field :attribute yang dipilih tidak valid.',
    'numeric'              => 'Field :attribute harus sebuah angka.',
    'old_password'         => 'Password tidak cocok dengan password lama.',
    'present'              => 'Field :attribute harus ada tapi boleh kosong.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => 'Field :attribute harus diisi.',
    'required_if'          => 'Field :attribute tidak boleh kosong ketika :other adalah :value.',
    'required_unless'      => 'Field :attribute tidak boleh kosong kecuali jika :other ada dalam :values.',
    'required_with'        => 'Field :attribute tidak boleh kosong ketika :values ada.',
    'required_with_all'    => 'Field :attribute tidak boleh kosong ketika :values ada.',
    'required_without'     => 'Field :attribute tidak boleh kosong ketika :values tidak ada.',
    'required_without_all' => 'Field :attribute tidak boleh kosong ketika tidak ada :values.',
    'same'                 => 'Field :attribute dan :other harus cocok.',
    'size'                 => [
        'numeric' => 'Field :attribute harus :size.',
        'file'    => 'Field :attribute harus :size kilobytes.',
        'string'  => 'Field :attribute harus :size karakter.',
        'array'   => 'Field :attribute harus memiliki :size item.',
    ],
    'string'               => 'Field :attribute harus sebuah string.',
    'timezone'             => 'Field :attribute harus sebuah zona waktu yang valid.',
    'unique'               => 'Field :attribute sudah terpakai. Masukkan yang lain.',
    'uploaded'             => 'Field :attribute gagal diupload.',
    'url'                  => 'Field :attribute salah.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        //Pembelian
        'PBN_KODE' => 'No Pembelian',
        'PBN_TOTAL_BARANG' => 'Jumlah Barang',
        'PBN_HARGA' => 'Harga',
        'PBN_TANGGAL' => 'Tanggal Pembelian',

        //Penjualan
        'PJN_KODE' => 'Nomor PO',
        'PJN_TGL_PEMESANAN' => 'Tgl Pemesanan',
        'PJN_TGL_PENGIRIMAN' => 'Tgl Pengiriman',
        'PJN_TGL_JATUH_TEMPO' => 'Tgl Jatuh Tempo',
        'PJN_HARGA_JUAL' => 'Harga Jual',
        'PJN_JML_1' => 'Jumlah Barang',
        'PBN_KODE_S_1' => 'Kode Pembelian Pemasok 1',
        'PJN_JML_S_1' => 'Jumlah Pemasok 1',
        'PBN_KODE_S_2' => 'Kode Pembelian Pemasok 2',
        'PJN_JML_S_2' => 'Jumlah Pemasok 2',
        'PBN_KODE_S_3' => 'Kode Pembelian Pemasok 3',
        'PJN_JML_S_3' => 'Jumlah Pemasok 3',
        'PJN_HPP_PER_KG' => 'HPP Per KG',
        'PJN_LAMA_KREDIT' => 'Lama Kredit',
        'C_NAMA' => 'Nama',
        'C_TELP' => 'Telp',
        'C_ALAMAT_PENGIRIMAN' => 'Alamat Penagihan',
        'C_FAX' => 'Fax',
        'C_REK_NAMA' => 'Atas Nama',
        'C_REK_BANK' => 'Bank',
        'C_REK_NO' => 'No Rekening',
        'S_NAMA' => 'Nama',
        'S_TELP' => 'Telp',
        'S_FAX' => 'Fax',
        'S_CP_EMAIL' => 'Email',
        'S_CP_INFORMASI' => 'Informasi',
        'S_ALAMAT' => 'Alamat'
    ],

];
