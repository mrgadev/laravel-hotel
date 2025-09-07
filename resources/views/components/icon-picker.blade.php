<!-- resources/views/components/icon-picker.blade.php -->
<div>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    
    <style>
        .icon-trigger {
            cursor: pointer;
            padding: 10px;
            border: 1px solid #976033;
            color: #976033;
            background-color: ghostwhite;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        
        .icon-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        
        .icon-modal-content {
            position: relative;
            background: white;
            width: 90%;
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            border-radius: 8px;
            max-height: 85vh;
            overflow-y: auto;
        }
        
        .icon-search {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }
        
        .icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 12px;
            margin-top: 20px;
        }
        
        .icon-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px;
            border: 1px solid #eee;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            min-height: 80px;
        }
        
        .icon-item:hover {
            background: #f5f5f5;
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .icon-item.selected {
            background: #e3f2fd;
            border-color: #976033;
            box-shadow: 0 2px 8px rgba(33, 150, 243, 0.3);
        }
        
        .icon-name {
            font-size: 11px;
            margin-top: 8px;
            text-align: center;
            word-break: break-word;
            line-height: 1.2;
        }
        
        .icon-preview {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .close-modal {
            position: absolute;
            right: 20px;
            top: 20px;
            cursor: pointer;
            font-size: 28px;
            color: #666;
        }
        
        .close-modal:hover {
            color: #333;
        }
        
        .icon-categories {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            overflow-x: auto;
            padding-bottom: 10px;
        }
        
        .category-btn {
            padding: 8px 16px;
            border: 1px solid #976033;
            background-color: ghostwhite;
            color: #976033;
            border-radius: 20px;
            cursor: pointer;
            white-space: nowrap;
            font-size: 14px;
            transition: all 0.2s;
        }
        
        .category-btn.active {
            background: #976033;
            color: white;
            border-color: #976033;
        }
        
        .category-btn:hover:not(.active) {
            background: #ffe6e6;
        }
        
        .icon-count {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
    </style>

    <!-- Icon Trigger Button -->
    <div class="icon-trigger" onclick="openIconPicker()">
        <i class="material-icons-round" id="selected-icon-display">{{ $selected ?? 'add' }}</i>
        <span>Pilih Ikon</span>
    </div>

    <!-- Hidden Input -->
    <input type="hidden" name="icon" id="icon-input" value="{{ $selected ?? '' }}">

    <!-- Icon Modal -->
    <div class="icon-modal" id="icon-modal">
        <div class="icon-modal-content">
            <span class="close-modal" onclick="closeIconPicker()">&times;</span>
            
            <h3 class="text-2xl font-medium text-primary-700 mb-5">Pilih Ikon Material</h3>
            
            <div class="icon-preview">
                <strong>Ikon yang Dipilih:</strong>
                <i class="material-icons-round" id="preview-icon" style="font-size: 24px;">{{ $selected ?? 'add' }}</i>
                <span id="preview-icon-name" style="font-weight: 500;">{{ $selected ?? 'add' }}</span>
            </div>
            
            <input type="text" class="icon-search" placeholder="Cari ikon... (contoh: rumah, pengaturan, email)" onkeyup="searchIcons()">
            
            <div class="icon-categories" id="icon-categories"></div>
            
            <div class="icon-count" id="icon-count"></div>
            
            <div class="icon-grid" id="icon-grid"></div>
        </div>
    </div>

    <script>
        // Mapping ikon dengan nama Indonesia dan nama asli
        const iconData = {
            'Aksi': {
                'rumah': 'home',
                'pengaturan': 'settings',
                'cari': 'search',
                'selesai': 'done',
                'menu': 'menu',
                'favorit': 'favorite',
                'kilat': 'bolt',
                'matikan': 'toggle_off',
                'kunci': 'key',
                'keranjang_checkout': 'shopping_cart_checkout',
                'blokir': 'block',
                'aksesibilitas': 'settings_accessibility',
                'layar_penuh': 'fullscreen',
                'tukar_horizontal': 'swap_horiz',
                'unggah': 'upload',
                'token': 'token',
                'geser_kiri': 'swipe_left',
                'geser_atas': 'swipe_up',
                'keranjang_belanja': 'shopping_cart',
                'deskripsi': 'description',
                'keluar': 'logout',
                'kelola_akun': 'manage_accounts',
                'sidik_jari': 'fingerprint',
                'masuk': 'login',
                'bayar': 'paid',
                'tas_belanja': 'shopping_bag',
                'trending_naik': 'trending_up',
                'lingkaran_akun': 'account_circle',
                'visibilitas': 'visibility',
                'favorit_border': 'favorite_border',
                'kunci_tutup': 'lock',
                'terverifikasi': 'verified',
                'jadwal': 'schedule',
                'wajah': 'face',
                'riwayat': 'history',
                'bangun': 'build',
                'cetak': 'print',
                'panel_admin': 'admin_panel_settings',
                'tabungan': 'savings',
                'kwitansi': 'receipt',
                'nilai': 'grade',
                'ruangan': 'room',
                'kunci_buka': 'lock_open',
                'bookmark': 'bookmark',
                'pembayaran': 'payment',
                'aksi_tertunda': 'pending_actions',
                'jelajahi': 'explore',
                'hewan_peliharaan': 'pets',
                'keranjang_basket': 'shopping_basket',
                'tips_update': 'tips_and_updates',
                'kartu_hadiah': 'card_giftcard',
                'jempol_naik': 'thumb_up_off_alt',
                'lihat_ar': 'view_in_ar',
                'dns': 'dns',
                'tugas_selesai': 'assignment_turned_in',
                'lepas_landas': 'flight_takeoff',
                'palu': 'gavel',
                'buku': 'book',
                'terjemah': 'translate',
                'roket_peluncuran': 'rocket_launch',
                'aksesibilitas_2': 'accessibility',
                'tambah_tugas': 'add_task',
                'dashboard_kustom': 'dashboard_customize',
                'tukar': 'redeem',
                'kerja_grup': 'group_work',
                'lampu_malam': 'nightlight_round',
                'query_builder': 'query_builder',
                'notifikasi_lingkaran': 'circle_notifications',
                'dapat_diakses': 'accessible',
                'offline_bolt': 'offline_bolt',
                'rasio_aspek': 'aspect_ratio',
                'opacity': 'opacity',
                'komuter': 'commute',
                'tur': 'tour',
                'sidebar': 'view_sidebar',
                'tol': 'toll',
                'wanita_hamil': 'pregnant_woman',
                'rencana_berikutnya': 'next_plan',
                'riwayat_kerja': 'work_history',
                'kartu_kredit_off': 'credit_card_off',
                'timeline': 'view_timeline',
                'lampu_outline': 'lightbulb_outline',
                'kunci_orang': 'lock_person',
                'galeri': 'browse_gallery',
                'tambah_rumah': 'add_home',
                'tampilan_kompak': 'view_compact_alt',
                'lebar': 'width_wide',
                'error': 'error',
                'peringatan': 'warning',
                'info': 'info',
                'bantuan': 'help',
                'jual': 'sell',
                'termostat': 'thermostat',
                'widget': 'widgets'
            },
            
            'Navigasi': {
                'panah_kembali': 'arrow_back',
                'panah_maju': 'arrow_forward',
                'menu_nav': 'menu',
                'lebih_vertikal': 'more_vert',
                'pembayaran_nav': 'payments',
                'centang': 'check',
                'kampanye': 'campaign',
                'peta_rumah_kerja': 'maps_home_work',
                'toggle_legenda': 'legend_toggle',
                'arah_asisten': 'assistant_direction',
                'tambah_rumah_kerja': 'add_home_work',
                'grafik_pivot': 'pivot_table_chart',
                'mode_terang': 'light_mode',
                'mode_gelap': 'dark_mode',
                'tugas_nav': 'task',
                'atas': 'keyboard_arrow_up',
                'bawah': 'keyboard_arrow_down',
                'kiri': 'keyboard_arrow_left',
                'kanan': 'keyboard_arrow_right',
                'pertama': 'first_page',
                'terakhir': 'last_page',
                'expand_lebih': 'expand_more',
                'expand_kurang': 'expand_less'
            },
            
            'Komunikasi': {
                'email': 'mail',
                'pesan': 'message',
                'telepon': 'phone',
                'chat': 'chat',
                'lokasi': 'location_on',
                'bisnis': 'business',
                'daftar_alt': 'list_alt',
                'kunci_vpn': 'vpn_key',
                'email_alternatif': 'alternate_email',
                'gelembung_chat': 'chat_bubble',
                'qr_code': 'qr_code_2',
                'hub': 'hub',
                'impor_kontak': 'import_contacts',
                'jam_pasir': 'hourglass_bottom',
                'rss_feed': 'rss_feed',
                'email_terbaca': 'mark_email_read',
                'email_belum_terbaca': 'mark_email_unread',
                'akhiri_panggilan': 'call_end',
                'dialpad': 'dialpad',
                'scanner_dokumen': 'document_scanner',
                'batal_presentasi': 'cancel_presentation',
                'menara_seluler': 'cell_tower',
                'volume_dering': 'ring_volume',
                'duo': 'duo',
                'pesan_suara': 'voicemail',
                'gabung_panggilan': 'call_merge',
                'spoke': 'spoke',
                'kontak_darurat': 'contact_emergency',
                'jeda_presentasi': 'pause_presentation',
                'wifi_panggilan': 'wifi_calling',
                'nat': 'nat',
                'lanskap_saat_ini': 'stay_current_landscape',
                'forum': 'forum',
                'komentar': 'comment',
                'textsms': 'textsms'
            },
            
            'File': {
                'folder': 'folder',
                'salin_file': 'file_copy',
                'unggah_cloud': 'cloud_upload',
                'lampiran': 'attachment',
                'unduh': 'download',
                'cloud': 'cloud',
                'koran': 'newspaper',
                'folder_berbagi': 'folder_shared',
                'persetujuan': 'approval',
                'ruang_kerja': 'workspaces',
                'topik': 'topic',
                'lingkaran_cloud': 'cloud_circle',
                'tambah_file': 'add',
                'hapus_file': 'remove',
                'buat': 'create',
                'unggah_file': 'upload',
                'kirim': 'send',
                'inventori': 'inventory_2',
                'bendera': 'flag',
                'kilat_file': 'bolt',
                'hitung': 'calculate',
                'perisai': 'shield',
                'kotak_masuk': 'inbox',
                'surat_suara': 'ballot',
                'bendera_outline': 'outlined_flag',
                'tempat_vote': 'where_to_vote',
                'kaki_persegi': 'square_foot',
                'gelombang': 'waves',
                'cara_vote': 'how_to_vote',
                'akhir_pekan': 'weekend',
                'gerakan': 'gesture',
                'mendatang': 'upcoming',
                'atribusi': 'attribution',
                'cerita_web': 'web_stories',
                'minggu_depan': 'next_week',
                'wawasan': 'insights',
                'tempel_konten': 'content_paste',
                'cara_daftar': 'how_to_reg',
                'aliran': 'stream',
                'dokumen': 'description',
                'artikel': 'article',
                'catatan': 'note'
            },
            
            'Sosial': {
                'orang': 'person',
                'grup': 'group',
                'bagikan': 'share',
                'notifikasi': 'notifications',
                'kelompok': 'groups',
                'publik': 'public',
                'acara_emoji': 'emoji_events',
                'teknik': 'engineering',
                'konstruksi': 'construction',
                'tetes_air': 'water_drop',
                'kota_lokasi': 'location_city',
                'emosi_emoji': 'emoji_emotions',
                'esports': 'sports_esports',
                'puas': 'sentiment_satisfied',
                'sains': 'science',
                'objek_emoji': 'emoji_objects',
                'kue': 'cake',
                'orang_emoji': 'emoji_people',
                'apa_panas': 'whatshot',
                'perbaikan_diri': 'self_improvement',
                'domain': 'domain',
                'rekomendasikan': 'recommend',
                'daur_ulang': 'recycling',
                'agen_real_estate': 'real_estate_agent',
                'arsitektur': 'architecture',
                'hiking': 'hiking',
                'masker': 'masks',
                'koper': 'luggage',
                'keragaman_3': 'diversity_3',
                'minat': 'interests',
                'malam_tinggal': 'night_stay',
                'tempat_tidur_raja': 'king_bed',
                'kompos': 'compost',
                'basket': 'sports_basketball',
                'makanan_minuman_emoji': 'emoji_food_beverage',
                'kuki': 'cookie',
                'dompet': 'wallet',
                'lansia': 'elderly',
                'tambah_moderator': 'add_moderator',
                'skala': 'scale',
                'perapian': 'fireplace',
                'sarang': 'hive',
                'voli': 'sports_volleyball',
                'keragaman_2': 'diversity_2',
                'tambah_domain': 'domain_add',
                'wajah_6': 'face_6',
                'wajah_4': 'face_4',
                'wajah_3': 'face_3',
                'wanita_lansia': 'elderly_woman',
                'banjir': 'flood',
                'tanpa_koper': 'no_luggage',
                'keluarga': 'family_restroom',
                'anak': 'child_care',
                'bayi': 'baby_changing_station'
            },
            
            'Peta & Tempat': {
                'pengiriman_lokal': 'local_shipping',
                'buku_menu': 'menu_book',
                'penawaran_lokal': 'local_offer',
                'lencana': 'badge',
                'peta': 'map',
                'restoran': 'restaurant',
                'mobil_arah': 'directions_car',
                'pemadam_kebakaran': 'local_fire_department',
                'aktivisme_sukarelawan': 'volunteer_activism',
                'penerbangan': 'flight',
                'mal_lokal': 'local_mall',
                'dekat_saya': 'near_me',
                'arah_lari': 'directions_run',
                'menu_restoran': 'restaurant_menu',
                'perayaan': 'celebration',
                'makan_siang': 'lunch_dining',
                'perpustakaan_lokal': 'local_library',
                'taman': 'park',
                'atm_lokal': 'local_atm',
                'aktivitas_lokal': 'local_activity',
                'pin_orang': 'person_pin',
                'layanan_desain': 'design_services',
                'bus_arah': 'directions_bus',
                'kafe_lokal': 'local_cafe',
                'pengiriman_makan': 'delivery_dining',
                'polisi_lokal': 'local_police',
                'sepeda_arah': 'directions_bike',
                'makanan_cepat': 'fastfood',
                'layanan_pembersihan': 'cleaning_services',
                'hotel': 'hotel',
                'layanan_perbaikan_rumah': 'home_repair_services',
                'navigasi': 'navigation',
                'toko_kelontong': 'local_grocery_store',
                'berlian': 'diamond',
                'kereta': 'train',
                'parkir_lokal': 'local_parking',
                'toko_bunga': 'local_florist',
                'pabrik': 'factory',
                'uang': 'money',
                'kantor_pos': 'local_post_office',
                'arah': 'directions',
                'roda_dua': 'two_wheeler',
                'tambah_bisnis': 'add_business',
                'lalu_lintas': 'traffic',
                'perahu_arah': 'directions_boat',
                'gudang': 'warehouse',
                'bar_lokal': 'local_bar',
                'pertanian': 'agriculture',
                'darurat': 'emergency',
                'sepeda_pedal': 'pedal_bike',
                '360_derajat': '360',
                'minuman_keras': 'liquor',
                'bandara_lokal': 'local_airport',
                'taksi_lokal': 'local_taxi',
                'hujan_es': 'hail',
                'makan_lokal': 'local_dining',
                'bus_terisi': 'directions_bus_filled',
                'percetakan_lokal': 'local_printshop',
                'komedi_teater': 'theater_comedy',
                'pizza_lokal': 'local_pizza',
                'hutan': 'forest',
                'transfer_stasiun': 'transfer_within_a_station',
                'makan_malam': 'dinner_dining',
                'toko_roti': 'bakery_dining',
                'bar_anggur': 'wine_bar',
                'medan': 'terrain',
                'direktori_mal': 'store_mall_directory',
                'papan_keberangkatan': 'departure_board',
                'kehidupan_malam': 'nightlife',
                'perangkat_keras': 'hardware',
                'apotek_lokal': 'local_pharmacy',
                'museum': 'museum',
                'stasiun_ev': 'ev_station',
                'mobil_listrik': 'electric_car',
                'lihat_lokal': 'local_see',
                'festival': 'festival',
                'pipa': 'plumbing',
                'sewa_mobil': 'car_rental',
                'informasi_medis': 'medical_information',
                'gereja': 'church',
                'pengendalian_hama': 'pest_control',
                'edit_atribut': 'edit_attributes',
                'perbaikan_mobil': 'car_repair',
                'moped': 'moped',
                'tram': 'tram',
                'kereta_bawah_tanah': 'subway',
                'lurus': 'straight',
                'hvac': 'hvac',
                'papan_petunjuk': 'signpost',
                'bioskop_lokal': 'local_movies',
                'brunch': 'brunch_dining',
                'masuk_keluar_transit': 'transit_enterexit',
                'transit_arah': 'directions_transit',
                'stadion': 'stadium',
                'masjid': 'mosque',
                'telur': 'egg',
                'kalibrasi_kompas': 'compass_calibration',
                'bermain_lokal': 'local_play',
                'perbaikan_ban': 'tire_repair',
                'sos': 'sos',
                'kelas_penerbangan': 'flight_class',
                'truk_pemadam': 'fire_truck',
                'sinagog': 'synagogue',
                'kuil_hindu': 'temple_hindu',
                'hidran_kebakaran': 'fire_hydrant_alt',
                'apartemen': 'apartment',
                'toko_depan': 'storefront',
                'pusat_bisnis': 'business_center',
                'spa': 'spa',
                'ruang_rapat': 'meeting_room',
                'pondok': 'cottage',
                'ruang_penitipan': 'checkroom',
                'rumput': 'grass',
                'akses_pantai': 'beach_access',
                'kolam': 'pool',
                'antar_jemput_bandara': 'airport_shuttle',
                'sarapan_gratis': 'free_breakfast',
                'villa': 'villa',
                'bebas_rokok': 'smoke_free',
                'bak_air_panas': 'hot_tub',
                'pemadam_api': 'fire_extinguisher',
                'balkon': 'balcony',
                'setrika': 'iron',
                'bungalo': 'bungalow',
                'garasi': 'garage',
                'shower': 'shower'
            },
            
            'Audio & Video': {
                'putar': 'play_arrow',
                'putar_lingkaran': 'play_circle',
                'mikrofon': 'mic',
                'kamera_video': 'videocam',
                'volume_naik': 'volume_up',
                'putar_ulang': 'replay',
                'berhenti': 'stop',
                'film': 'movie',
                'web_av': 'web',
                'perpustakaan_video': 'video_library',
                'pendengaran': 'hearing',
                'aktor_terbaru': 'recent_actors',
                'subtitle': 'subtitles',
                'permainan': 'games',
                'radio': 'radio',
                'tambah_ke_antrian': 'add_to_queue',
                'airplay': 'airplay',
                'panggilan_aksi': 'call_to_action',
                'hd': 'hd',
                'label_video': 'video_label',
                'trek_seni': 'art_track',
                '4k': '4k',
                'suara_surround': 'surround_sound',
                'sd': 'sd',
                '8k': '8k',
                'tv_langsung': 'live_tv',
                'video_personal': 'personal_video',
                'volume_bisu': 'volume_off',
                'volume_turun': 'volume_down',
                'jeda': 'pause',
                'lewati_berikutnya': 'skip_next',
                'lewati_sebelumnya': 'skip_previous',
                'maju_cepat': 'fast_forward',
                'mundur_cepat': 'fast_rewind',
                'shuffle': 'shuffle',
                'ulangi': 'repeat',
                'ulangi_satu': 'repeat_one'
            },
            
            'Notifikasi': {
                'agen_dukungan': 'support_agent',
                'wifi': 'wifi',
                'pohon_akun': 'account_tree',
                'sinkronisasi': 'sync',
                'acara_tersedia': 'event_available',
                'catatan_acara': 'event_note',
                'sms': 'sms',
                'tv_langsung_notif': 'live_tv',
                'video_on_demand': 'ondemand_video',
                'eta_berkendara': 'drive_eta',
                'lebih_banyak': 'more',
                'wc': 'wc',
                'jangan_ganggu': 'do_not_disturb',
                'daya': 'power',
                'kunci_vpn': 'vpn_lock',
                'enkripsi_ditingkatkan': 'enhanced_encryption',
                'adb': 'adb',
                'kursi_pesawat': 'airline_seat_recline_extra',
                'roller_pencarian_gambar': 'imagesearch_roller',
                'sinyal_seluler': 'signal_cellular_4_bar',
                'baterai_penuh': 'battery_full',
                'baterai_rendah': 'battery_alert',
                'bluetooth': 'bluetooth',
                'bluetooth_terhubung': 'bluetooth_connected',
                'mode_pesawat': 'airplanemode_active',
                'hotspot': 'wifi_tethering',
                'data_seluler': 'signal_cellular_alt'
            },
            
            'Perangkat Keras': {
                'smartphone': 'smartphone',
                'komputer': 'computer',
                'keamanan': 'security',
                'desktop_windows': 'desktop_windows',
                'laptop': 'laptop',
                'headphone': 'headphones',
                'tv': 'tv',
                'pos_penjualan': 'point_of_sale',
                'router': 'router',
                'tautan_telepon': 'phonelink',
                'speaker': 'speaker',
                'tv_terhubung': 'connected_tv',
                'tablet': 'tablet',
                'jam_tangan': 'watch',
                'keyboard': 'keyboard',
                'mouse': 'mouse',
                'monitor': 'monitor',
                'printer': 'print',
                'scanner': 'scanner',
                'kamera': 'camera_alt',
                'memori': 'memory',
                'penyimpanan': 'storage',
                'usb': 'usb',
                'sd_card': 'sd_card'
            },
            
            'Cuaca': {
                'cerah': 'wb_sunny',
                'berawan': 'cloud',
                'hujan': 'grain',
                'salju': 'ac_unit',
                'petir': 'flash_on',
                'kabut': 'cloud',
                'angin': 'air',
                'panas': 'whatshot',
                'dingin': 'ac_unit',
                'lembab': 'opacity',
                'kering': 'water_drop',
                'badai': 'thunderstorm'
            },
            
            'Transportasi': {
                'mobil': 'directions_car',
                'bus': 'directions_bus',
                'kereta_api': 'train',
                'pesawat': 'flight',
                'kapal': 'directions_boat',
                'sepeda': 'directions_bike',
                'jalan_kaki': 'directions_walk',
                'taksi': 'local_taxi',
                'motor': 'motorcycle',
                'truk': 'local_shipping',
                'helikopter': 'flight',
                'perahu_layar': 'sailing'
            }
        };

        let currentCategory = 'all';
        let allIcons = [];
        
        // Flatten all icons with Indonesian names for search
        function initializeIconPicker() {
            allIcons = [];
            Object.keys(iconData).forEach(category => {
                Object.keys(iconData[category]).forEach(indonesianName => {
                    const originalName = iconData[category][indonesianName];
                    allIcons.push({
                        indonesian: indonesianName,
                        original: originalName,
                        category: category
                    });
                });
            });
            
            // Create category buttons
            const categoriesContainer = document.getElementById('icon-categories');
            categoriesContainer.innerHTML = `
                <span class="category-btn active" onclick="filterByCategory('all')">Semua</span>
                ${Object.keys(iconData).map(category => 
                    `<span class="category-btn" onclick="filterByCategory('${category}')">${category}</span>`
                ).join('')}
            `;
            
            // Load initial icons
            loadIcons();
        }

        function loadIcons() {
            const grid = document.getElementById('icon-grid');
            const countElement = document.getElementById('icon-count');
            grid.innerHTML = '';
            
            let iconsToShow = [];
            if (currentCategory === 'all') {
                iconsToShow = allIcons;
            } else {
                iconsToShow = allIcons.filter(icon => icon.category === currentCategory);
            }
            
            countElement.textContent = `Menampilkan ${iconsToShow.length} ikon`;
            
            iconsToShow.forEach(iconInfo => {
                const div = document.createElement('div');
                div.className = 'icon-item';
                div.onclick = () => selectIcon(iconInfo.original, iconInfo.indonesian);
                div.innerHTML = `
                    <i class="material-icons-round" style="font-size: 24px;">${iconInfo.original}</i>
                    <span class="icon-name">${iconInfo.indonesian}</span>
                `;
                grid.appendChild(div);
            });
        }

        function searchIcons() {
            const searchTerm = document.querySelector('.icon-search').value.toLowerCase();
            const items = document.querySelectorAll('.icon-item');
            let visibleCount = 0;
            
            items.forEach(item => {
                const iconName = item.querySelector('.icon-name').textContent.toLowerCase();
                const isVisible = iconName.includes(searchTerm);
                item.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            });
            
            document.getElementById('icon-count').textContent = `Menampilkan ${visibleCount} ikon`;
        }

        function selectIcon(originalName, indonesianName) {
            // Update hidden input with original name
            document.getElementById('icon-input').value = originalName;
            
            // Update preview with original icon but show both names
            document.getElementById('preview-icon').textContent = originalName;
            document.getElementById('preview-icon-name').textContent = `${indonesianName} (${originalName})`;
            
            // Update trigger button icon
            document.getElementById('selected-icon-display').textContent = originalName;
            
            // Update selected state
            document.querySelectorAll('.icon-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Find and highlight the selected item
            const selectedItem = Array.from(document.querySelectorAll('.icon-item')).find(item => {
                const iconElement = item.querySelector('.material-icons-round');
                return iconElement && iconElement.textContent === originalName;
            });
            
            if (selectedItem) {
                selectedItem.classList.add('selected');
            }
        }

        function filterByCategory(category) {
            currentCategory = category;
            
            // Update active state of category buttons
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('active');
                if ((category === 'all' && btn.textContent === 'Semua') || 
                    btn.textContent === category) {
                    btn.classList.add('active');
                }
            });
            
            loadIcons();
        }

        function openIconPicker() {
            document.getElementById('icon-modal').style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeIconPicker() {
            document.getElementById('icon-modal').style.display = 'none';
            document.body.style.overflow = 'auto'; // Restore scrolling
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('icon-modal');
            if (event.target === modal) {
                closeIconPicker();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeIconPicker();
            }
        });

        // Initialize when document is ready
        document.addEventListener('DOMContentLoaded', initializeIconPicker);
    </script>
</div>