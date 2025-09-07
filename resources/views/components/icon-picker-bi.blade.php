<!-- resources/views/components/icon-picker.blade.php -->
<div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .icon-trigger {
            cursor: pointer;
            padding: 10px;
            border: 1px solid #5b3a1f;
            color: #5b3a1f;
            background-color: #eadfd6;
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
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .icon-search {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .icon-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 10px;
            margin-top: 20px;
        }
        
        .icon-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .icon-item:hover {
            background: #f5f5f5;
            transform: scale(1.05);
        }
        
        .icon-item.selected {
            background: #e3f2fd;
            border-color: #2196f3;
        }
        
        .icon-name {
            font-size: 12px;
            margin-top: 5px;
            text-align: center;
            word-break: break-all;
        }
        
        .icon-preview {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .close-modal {
            position: absolute;
            right: 20px;
            top: 20px;
            cursor: pointer;
            font-size: 24px;
        }
        
        .icon-categories {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            overflow-x: auto;
            padding-bottom: 10px;
        }
        
        .category-btn {
            padding: 5px 15px;
            border: 1px solid #5b3a1f;
            background-color: #eadfd6;
            color: #5b3a1f;
            border-radius: 20px;
            cursor: pointer;
            white-space: nowrap;
        }
        
        .category-btn.active {
            background: #5b3a1f;
            color: white;
            border-color: #5b3a1f;
        }
    </style>

    <!-- Icon Trigger Button -->
    <div class="icon-trigger" onclick="openIconPicker()">
        <i class="bi bi-{{ $selected ?? 'plus' }}" id="selected-icon-display"></i>
        {{-- <i class="material-icons-round" id="selected-icon-display">{{ $selected ?? 'add' }}</i> --}}
        <span>Pilih Ikon</span>
    </div>

    <!-- Hidden Input -->
    <input type="hidden" name="{{ $name ?? 'icon' }}" id="icon-input" value="{{ $selected ?? '' }}">

    <!-- Icon Modal -->
    <div class="icon-modal" id="icon-modal">
        <div class="icon-modal-content">
            <span class="close-modal" onclick="closeIconPicker()">&times;</span>
            
            <h3 class="text-2xl font-medium text-primary-700 mb-5">Pilih Ikon</h3>
            
            <div class="icon-preview">
                Ikon yang Dipilih: 
                <i class="bi bi-{{ $selected ?? 'plus' }}" id="preview-icon"></i>
                {{-- <i class="material-icons-round" id="preview-icon">{{ $selected ?? 'add' }}</i> --}}
                <span id="preview-icon-name">{{ $selected ?? 'plus' }}</span>
            </div>
            
            <input type="text" class="icon-search" placeholder="Search icons..." onkeyup="searchIcons()">
            
            <div class="icon-categories" id="icon-categories"></div>
            
            <div class="icon-grid" id="icon-grid"></div>
        </div>
    </div>

    <script>
        const iconCategories = {

            'Communication': ['amd', 'android', 'phone', 'chat', 'apple', 'chat', 'check-circle', 'clipboard', 'clock', 'cloud', 'compass', 'credit-card', 'cursor', 'facebook', 'messenger', 'meta', 'whatsapp', 'instagram', 'threads', 'twitter', 'twitter-x', 'twitch', 'pinterest', 'tiktok', 'alexa', 'behance', 'discord', 'dribbble', 'github', 'gitlab', 'google', 'line', 'linkedin', 'mastodon', 'medium', 'quora', 'reddit', 'signal', 'skype', 'slack', 'snapchat', 'spotify', 'telegram', 'vimeo', 'youtube'],

            
            // Add more categories as needed
        };

        let currentCategory = 'all';
        
        function initializeIconPicker() {
            // Create category buttons
            const categoriesContainer = document.getElementById('icon-categories');
            categoriesContainer.innerHTML = `
                <span class="category-btn active" onclick="filterByCategory('all')">All</span>
                ${Object.keys(iconCategories).map(category => 
                    `<span class="category-btn" onclick="filterByCategory('${category}')">${category}</span>`
                ).join('')}
            `;
            
            // Load initial icons
            loadIcons();
        }

        function loadIcons() {
            const grid = document.getElementById('icon-grid');
            grid.innerHTML = '';
            
            let icons = [];
            if (currentCategory === 'all') {
                icons = Object.values(iconCategories).flat();
            } else {
                icons = iconCategories[currentCategory] || [];
            }
            
            icons.forEach(icon => {
                const div = document.createElement('div');
                div.className = 'icon-item';
                div.onclick = () => selectIcon(icon);
                div.innerHTML = `
                <i class="bi bi-${icon}"></i>
                <span class="icon-name">${icon}</span>
                `;
                grid.appendChild(div);
            });
        }

        function searchIcons() {
            const searchTerm = document.querySelector('.icon-search').value.toLowerCase();
            const items = document.querySelectorAll('.icon-item');
            
            items.forEach(item => {
                const iconName = item.querySelector('.icon-name').textContent.toLowerCase();
                item.style.display = iconName.includes(searchTerm) ? '' : 'none';
            });
        }

        function selectIcon(iconName) {
            // Update hidden input
            document.getElementById('icon-input').value = iconName;
            
            // Update preview
            document.getElementById('preview-icon').textContent = iconName;
            document.getElementById('preview-icon-name').textContent = iconName;
            
            // Update trigger button icon
            document.getElementById('selected-icon-display').textContent = iconName;
            
            // Update selected state
            document.querySelectorAll('.icon-item').forEach(item => {
                item.classList.remove('selected');
                if (item.querySelector('.icon-name').textContent === iconName) {
                    item.classList.add('selected');
                }
            });
        }

        function filterByCategory(category) {
            currentCategory = category;
            
            // Update active state of category buttons
            document.querySelectorAll('.category-btn').forEach(btn => {
                btn.classList.remove('active');
                if (btn.textContent.toLowerCase() === category.toLowerCase()) {
                    btn.classList.add('active');
                }
            });
            
            loadIcons();
        }

        function openIconPicker() {
            document.getElementById('icon-modal').style.display = 'block';
        }

        function closeIconPicker() {
            document.getElementById('icon-modal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('icon-modal');
            if (event.target === modal) {
                closeIconPicker();
            }
        }

        // Initialize when document is ready
        document.addEventListener('DOMContentLoaded', initializeIconPicker);
    </script>
</div>