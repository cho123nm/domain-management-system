<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        /* Music Player Pro Styles */
        :root {
            --accent-primary: #00d4ff;
            --accent-secondary: #7c3aed;
            --glass-bg: rgba(255,255,255,0.03);
            --glass-border: rgba(255,255,255,0.08);
            --text-primary: #ffffff;
            --text-secondary: #a0a0a0;
            --text-muted: #6b7280;
            --card-bg: rgba(17,17,17,0.8);
            --hover-bg: rgba(255,255,255,0.05);
            --success-color: #22c55e;
            --error-color: #ef4444;
        }

        .music-player {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 20px;
            transition: all 0.4s cubic-bezier(0.4, 0.0, 0.2, 1);
            min-width: 320px;
            max-width: 400px;
            user-select: none;
        }

        .music-player.minimized {
            transform: translateX(calc(100% + 30px));
            opacity: 0;
            pointer-events: none;
        }

        .music-player.dragging {
            transition: none !important;
            transform: rotate(2deg) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,212,255,0.4);
            z-index: 99999;
            border-color: var(--accent-primary);
        }

        .music-player.dragging.minimized {
            transform: translateX(calc(100% + 30px)) rotate(2deg) scale(1.02);
        }

        /* Minimize Toggle Button */
        .minimize-toggle {
            position: fixed;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            z-index: 9998;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 25px 0 0 25px;
            padding: 15px 10px 15px 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .minimize-toggle.show {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .minimize-toggle:hover {
            background: var(--hover-bg);
            border-color: var(--accent-primary);
            transform: translateY(-50%) translateX(-5px);
        }

        .minimize-toggle-icon {
            color: var(--accent-primary);
            font-size: 18px;
            font-weight: bold;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
        }

        .minimize-toggle:hover .minimize-toggle-icon {
            transform: scale(1.2);
            color: var(--text-primary);
        }

        /* Music status indicator when minimized */
        .music-indicator {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9997;
            width: 50px;
            height: 50px;
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border: 2px solid var(--accent-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            animation: pulse-music 2s infinite;
        }

        .music-indicator.show {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .music-indicator:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 30px rgba(0,212,255,0.3);
        }

        .music-indicator-icon {
            color: var(--accent-primary);
            font-size: 20px;
            animation: rotate-music 3s linear infinite;
        }

        @keyframes pulse-music {
            0%, 100% { 
                border-color: var(--accent-primary);
                box-shadow: 0 0 0 0 rgba(0,212,255,0.4);
            }
            50% { 
                border-color: var(--accent-secondary);
                box-shadow: 0 0 0 10px rgba(0,212,255,0);
            }
        }

        @keyframes rotate-music {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .music-player.expanded {
            bottom: 20px;
            right: 20px;
            min-width: 380px;
        }

        .music-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            cursor: grab;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: 2px dashed transparent;
        }

        .music-header:hover {
            background: var(--hover-bg);
            border-color: var(--accent-primary);
            cursor: grab;
        }

        .music-header:active {
            cursor: grabbing;
        }

        .music-header.dragging {
            background: rgba(0,212,255,0.2);
            border-color: var(--accent-primary);
            cursor: grabbing;
        }

        .music-controls-header {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .minimize-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 6px;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .minimize-btn:hover {
            background: var(--hover-bg);
            color: var(--accent-primary);
            transform: scale(1.1);
        }

        .music-title {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
            pointer-events: none;
        }

        .drag-indicator {
            color: var(--text-muted);
            font-size: 16px;
            margin-left: auto;
            margin-right: 8px;
            opacity: 0.7;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .music-header:hover .drag-indicator {
            opacity: 1;
            color: var(--accent-primary);
            transform: scale(1.2);
        }

        .music-toggle-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 8px;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .music-toggle-btn:hover {
            background: var(--hover-bg);
            color: var(--accent-primary);
            transform: scale(1.1);
        }

        .music-input-section {
            display: none;
            margin-bottom: 16px;
        }

        .music-input-section.active {
            display: block;
            animation: slideDown 0.3s ease-out;
        }

        .input-group {
            margin-bottom: 12px;
        }

        .input-label {
            color: var(--text-secondary);
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 6px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .music-input {
            width: 100%;
            background: var(--card-bg);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 12px 16px;
            color: var(--text-primary);
            font-size: 0.9rem;
            font-family: monospace;
            transition: all 0.3s ease;
            resize: vertical;
            min-height: 80px;
        }

        .music-input:focus {
            outline: none;
            border-color: var(--accent-primary);
            background: rgba(0,212,255,0.05);
            box-shadow: 0 0 0 3px rgba(0,212,255,0.1);
        }

        .music-input::placeholder {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .input-buttons {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .music-btn {
            background: var(--accent-primary);
            color: #000;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            flex: 1;
        }

        .music-btn:hover {
            background: var(--accent-secondary);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,212,255,0.3);
        }

        .music-btn.secondary {
            background: var(--glass-bg);
            color: var(--text-secondary);
            border: 1px solid var(--glass-border);
        }

        .music-btn.secondary:hover {
            background: var(--hover-bg);
            color: var(--text-primary);
            border-color: var(--accent-primary);
        }

        .music-examples {
            margin-top: 12px;
            padding: 12px;
            background: rgba(124,58,237,0.05);
            border-radius: 10px;
            border: 1px solid rgba(124,58,237,0.1);
        }

        .examples-title {
            color: var(--accent-secondary);
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .example-link {
            display: block;
            color: var(--text-muted);
            font-size: 0.7rem;
            font-family: monospace;
            margin-bottom: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .example-link:hover {
            background: rgba(124,58,237,0.1);
            color: var(--accent-secondary);
        }

        .music-status {
            padding: 12px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 500;
            text-align: center;
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }

        .music-status.success {
            background: rgba(34,197,94,0.1);
            color: var(--success-color);
            border: 1px solid rgba(34,197,94,0.2);
        }

        .music-status.error {
            background: rgba(239,68,68,0.1);
            color: var(--error-color);
            border: 1px solid rgba(239,68,68,0.2);
        }

        .music-status.info {
            background: rgba(0,212,255,0.1);
            color: var(--accent-primary);
            border: 1px solid rgba(0,212,255,0.2);
        }

        .music-iframe-container {
            border-radius: 12px;
            overflow: hidden;
            margin-top: 12px;
            border: 1px solid var(--glass-border);
        }

        .music-iframe {
            width: 100%;
            height: 152px;
            border: none;
            background: var(--card-bg);
        }

        .platform-icons {
            display: flex;
            gap: 8px;
            margin-top: 8px;
            justify-content: center;
        }

        .platform-icon {
            width: 24px;
            height: 24px;
            background: var(--glass-bg);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid var(--glass-border);
        }

        .platform-icon:hover {
            background: var(--accent-primary);
            color: #000;
            transform: scale(1.1);
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div id="kt_app_footer" class="app-footer align-items-center justify-content-between">
        
        <div class="text-dark order-2 order-md-1">
            <span class="text-muted fw-semibold me-1">2025&copy;</span>
            <a href="https://www.facebook.com/thanh.vu.826734" target="_blank" class="text-gray-800 text-hover-primary"> CUNG CẤP BỞI <b class="text-danger"> THANHVU.NET V4 </b></a>
        </div>
    
        <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
            <li class="menu-item">
                <a href="/" target="_blank" class="menu-link px-2">About</a>
            </li>
            <li class="menu-item">
                <a href="https://www.facebook.com/thanh.vu.826734" target="_blank" class="menu-link px-2">Support</a>
            </li>
            <li class="menu-item">
                <a href="/" target="_blank" class="menu-link px-2">Purchase</a>
            </li>
        </ul>
    
    </div>

    <!-- Enhanced Music Player Pro -->
    <div class="music-player">
        <div class="music-header" id="musicHeader">
            <div class="music-title">
                🎵 Music Player Pro
            </div>
            <div class="drag-indicator" title="Kéo để di chuyển">⋮⋮⋮</div>
            <div class="music-controls-header">
                <button class="minimize-btn" onclick="toggleMinimize()" id="minimizeBtn" title="Thu nhỏ player">
                    ➖
                </button>
                <button class="music-toggle-btn" onclick="toggleMusicPlayer()" id="musicToggleBtn">
                    🎶
                </button>
            </div>
        </div>
        
        <div class="music-input-section" id="musicInputSection">
            <div class="input-group">
                <label class="input-label">Embed Code hoặc Link</label>
                <textarea 
                    class="music-input" 
                    id="musicInput" 
                    placeholder="Paste Spotify/YouTube/SoundCloud embed iframe code hoặc link...

VD: https://open.spotify.com/track/4iV5W9uYEdYUVa79Applpe
hoặc: <iframe src='...' width='100%' height='152'></iframe>"
                ></textarea>
            </div>
            
            <div class="input-buttons">
                <button class="music-btn" onclick="loadMusic()">🚀 Load Music</button>
                <button class="music-btn secondary" onclick="clearMusic()">🗑️ Clear</button>
            </div>
            
            <div class="music-examples">
                <div class="examples-title">📋 Ví dụ Links:</div>
                <div class="example-link" onclick="useExample(this)" data-link="https://open.spotify.com/track/4iV5W9uYEdYUVa79Applpe">
                    🎵 Spotify Track
                </div>
                <div class="example-link" onclick="useExample(this)" data-link="https://www.youtube.com/watch?v=jfKfPfyJRdk">
                    📺 YouTube Video
                </div>
                <div class="example-link" onclick="useExample(this)" data-link="https://soundcloud.com/user-977421934/sets/lofi-hip-hop-mix">
                    🔊 SoundCloud Playlist
                </div>
            </div>
            
            <div class="platform-icons">
                <div class="platform-icon" title="Spotify">🎵</div>
                <div class="platform-icon" title="YouTube">📺</div>
                <div class="platform-icon" title="SoundCloud">🔊</div>
                <div class="platform-icon" title="Apple Music">🍎</div>
            </div>
        </div>
        
        <div class="music-status" id="musicStatus" style="display: none;">
            Click 🎶 để mở music player
        </div>
        
        <div id="musicContainer"></div>
    </div>

    <!-- Minimize Toggle Button (Shows when player is hidden) -->
    <div class="minimize-toggle" id="minimizeToggle" onclick="toggleMinimize()" title="Hiện music player">
        <div class="minimize-toggle-icon">◀</div>
    </div>

    <!-- Music Status Indicator (Shows when minimized and music is playing) -->
    <div class="music-indicator" id="musicIndicator" onclick="toggleMinimize()" title="Click to show music player">
        <div class="music-indicator-icon">🎵</div>
    </div>

    <script>
        // Enhanced Music Player Pro with FIXED Draggable & Minimize
        let musicPlayerVisible = false;
        let isMinimized = false;
        let isDragging = false;
        let dragOffset = { x: 0, y: 0 };
        let musicPlayer, musicHeader;

        // Minimize/Maximize functionality
        function toggleMinimize() {
            const player = document.querySelector('.music-player');
            const toggleBtn = document.getElementById('minimizeToggle');
            const indicator = document.getElementById('musicIndicator');
            const minimizeBtn = document.getElementById('minimizeBtn');
            const container = document.getElementById('musicContainer');
            
            isMinimized = !isMinimized;
            
            if (isMinimized) {
                // Minimize player
                player.classList.add('minimized');
                toggleBtn.classList.add('show');
                minimizeBtn.textContent = '➕';
                minimizeBtn.title = 'Mở rộng player';
                
                // Show music indicator if music is playing
                if (container && container.innerHTML.trim()) {
                    setTimeout(() => {
                        indicator.classList.add('show');
                    }, 400); // Wait for minimize animation
                }
                
                console.log('🔸 Music player minimized');
                showToast('🔸 Music player minimized', 'info');
                
                // Save minimize state
                localStorage.setItem('musicPlayerMinimized', 'true');
                
            } else {
                // Maximize player
                player.classList.remove('minimized');
                toggleBtn.classList.remove('show');
                indicator.classList.remove('show');
                minimizeBtn.textContent = '➖';
                minimizeBtn.title = 'Thu nhỏ player';
                
                console.log('🔹 Music player restored');
                showToast('🔹 Music player restored', 'info');
                
                // Remove minimize state
                localStorage.removeItem('musicPlayerMinimized');
            }
        }

        // Toast notification system
        function showToast(message, type = 'info') {
            // Remove existing toast
            const existingToast = document.querySelector('.toast-notification');
            if (existingToast) {
                existingToast.remove();
            }
            
            // Create toast
            const toast = document.createElement('div');
            toast.className = `toast-notification ${type}`;
            toast.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 99999;
                background: var(--glass-bg);
                backdrop-filter: blur(20px);
                border: 1px solid var(--glass-border);
                border-radius: 12px;
                padding: 12px 20px;
                color: var(--text-primary);
                font-size: 0.9rem;
                font-weight: 500;
                transition: all 0.3s ease;
                opacity: 0;
                transform: translateY(-20px);
            `;
            
            if (type === 'success') {
                toast.style.borderColor = 'var(--success-color)';
                toast.style.color = 'var(--success-color)';
            } else if (type === 'info') {
                toast.style.borderColor = 'var(--accent-primary)';
                toast.style.color = 'var(--accent-primary)';
            }
            
            toast.textContent = message;
            document.body.appendChild(toast);
            
            // Animate in
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            }, 100);
            
            // Auto remove
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }

        // Load minimize state
        function loadMinimizeState() {
            const wasMinimized = localStorage.getItem('musicPlayerMinimized') === 'true';
            if (wasMinimized) {
                // Small delay to ensure DOM is ready
                setTimeout(() => {
                    toggleMinimize();
                }, 500);
            }
        }
        
        // FIXED Draggable functionality
        function makeDraggable() {
            console.log('🎵 Initializing draggable...');
            
            musicPlayer = document.querySelector('.music-player');
            musicHeader = document.getElementById('musicHeader');
            
            if (!musicPlayer || !musicHeader) {
                console.error('❌ Music player elements not found!');
                return;
            }
            
            console.log('✅ Music player elements found');
            
            // Remove existing event listeners to avoid duplicates
            musicHeader.removeEventListener('mousedown', startDrag);
            musicHeader.removeEventListener('touchstart', startDragTouch);
            
            // Add event listeners
            musicHeader.addEventListener('mousedown', startDrag, { passive: false });
            document.addEventListener('mousemove', drag, { passive: false });
            document.addEventListener('mouseup', stopDrag);
            
            // Touch events for mobile
            musicHeader.addEventListener('touchstart', startDragTouch, { passive: false });
            document.addEventListener('touchmove', dragTouch, { passive: false });
            document.addEventListener('touchend', stopDrag);
            
            function startDrag(e) {
                console.log('🖱️ Mouse drag started');
                e.preventDefault();
                e.stopPropagation();
                
                isDragging = true;
                const rect = musicPlayer.getBoundingClientRect();
                dragOffset.x = e.clientX - rect.left;
                dragOffset.y = e.clientY - rect.top;
                
                musicPlayer.classList.add('dragging');
                musicHeader.classList.add('dragging');
                document.body.style.userSelect = 'none';
                
                console.log('✅ Drag initialized', { offsetX: dragOffset.x, offsetY: dragOffset.y });
            }
            
            function startDragTouch(e) {
                console.log('👆 Touch drag started');
                const touch = e.touches[0];
                e.preventDefault();
                e.stopPropagation();
                
                isDragging = true;
                const rect = musicPlayer.getBoundingClientRect();
                dragOffset.x = touch.clientX - rect.left;
                dragOffset.y = touch.clientY - rect.top;
                
                musicPlayer.classList.add('dragging');
                musicHeader.classList.add('dragging');
                document.body.style.userSelect = 'none';
            }
            
            function drag(e) {
                if (!isDragging) return;
                
                e.preventDefault();
                
                const x = e.clientX - dragOffset.x;
                const y = e.clientY - dragOffset.y;
                
                updatePosition(x, y);
            }
            
            function dragTouch(e) {
                if (!isDragging) return;
                
                e.preventDefault();
                const touch = e.touches[0];
                const x = touch.clientX - dragOffset.x;
                const y = touch.clientY - dragOffset.y;
                
                updatePosition(x, y);
            }
            
            function updatePosition(x, y) {
                // Keep within viewport bounds with padding
                const padding = 10;
                const maxX = window.innerWidth - musicPlayer.offsetWidth - padding;
                const maxY = window.innerHeight - musicPlayer.offsetHeight - padding;
                
                const boundedX = Math.max(padding, Math.min(x, maxX));
                const boundedY = Math.max(padding, Math.min(y, maxY));
                
                musicPlayer.style.left = boundedX + 'px';
                musicPlayer.style.top = boundedY + 'px';
                musicPlayer.style.right = 'auto';
                musicPlayer.style.bottom = 'auto';
                
                console.log('📍 Position updated:', { x: boundedX, y: boundedY });
            }
            
            function stopDrag(e) {
                if (!isDragging) return;
                
                console.log('🛑 Drag stopped');
                
                isDragging = false;
                musicPlayer.classList.remove('dragging');
                musicHeader.classList.remove('dragging');
                document.body.style.userSelect = '';
                
                // Save position to localStorage
                const rect = musicPlayer.getBoundingClientRect();
                const position = {
                    x: rect.left,
                    y: rect.top
                };
                localStorage.setItem('musicPlayerPosition', JSON.stringify(position));
                console.log('💾 Position saved:', position);
            }
        }
        
        // Load saved position
        function loadPlayerPosition() {
            const savedPosition = localStorage.getItem('musicPlayerPosition');
            if (savedPosition) {
                try {
                    const position = JSON.parse(savedPosition);
                    console.log('📂 Loading saved position:', position);
                    
                    // Validate position is within current viewport
                    const maxX = window.innerWidth - musicPlayer.offsetWidth;
                    const maxY = window.innerHeight - musicPlayer.offsetHeight;
                    
                    if (position.x >= 0 && position.x <= maxX && position.y >= 0 && position.y <= maxY) {
                        musicPlayer.style.left = position.x + 'px';
                        musicPlayer.style.top = position.y + 'px';
                        musicPlayer.style.right = 'auto';
                        musicPlayer.style.bottom = 'auto';
                        console.log('✅ Position loaded successfully');
                    } else {
                        console.log('⚠️ Saved position out of bounds, using default');
                    }
                } catch (error) {
                    console.error('❌ Error loading position:', error);
                }
            } else {
                console.log('ℹ️ No saved position found');
            }
        }
        
        // Reset position function
        function resetPosition() {
            musicPlayer.style.right = '30px';
            musicPlayer.style.bottom = '30px';
            musicPlayer.style.left = 'auto';
            musicPlayer.style.top = 'auto';
            localStorage.removeItem('musicPlayerPosition');
            console.log('🔄 Position reset to default');
        }
        
        function toggleMusicPlayer() {
            const musicSection = document.getElementById('musicInputSection');
            const musicStatus = document.getElementById('musicStatus');
            const toggleBtn = document.getElementById('musicToggleBtn');
            const player = document.querySelector('.music-player');
            
            musicPlayerVisible = !musicPlayerVisible;
            
            if (musicPlayerVisible) {
                musicSection.classList.add('active');
                musicStatus.style.display = 'none';
                toggleBtn.textContent = '✖️';
                player.classList.add('expanded');
                showMusicStatus('🎵 Music player opened! Paste your music link below.', 'info');
            } else {
                musicSection.classList.remove('active');
                musicStatus.style.display = 'block';
                musicStatus.textContent = 'Click 🎶 để mở music player';
                toggleBtn.textContent = '🎶';
                player.classList.remove('expanded');
            }
        }

        function showMusicStatus(message, type = 'info') {
            const status = document.getElementById('musicStatus');
            status.textContent = message;
            status.className = `music-status ${type}`;
            status.style.display = 'block';
            
            if (type === 'success' || type === 'error') {
                setTimeout(() => {
                    status.style.display = 'none';
                }, 5000);
            }
        }

        // Save current music state
        function saveMusicState(embedCode, isPlaying = true) {
            const musicState = {
                embedCode: embedCode,
                isPlaying: isPlaying,
                timestamp: Date.now(),
                url: window.location.href
            };
            localStorage.setItem('musicPlayerState', JSON.stringify(musicState));
            console.log('💾 Music state saved:', musicState);
        }

        // Load and restore music state
        function loadMusicState() {
            const savedState = localStorage.getItem('musicPlayerState');
            if (savedState) {
                try {
                    const musicState = JSON.parse(savedState);
                    const timeDiff = Date.now() - musicState.timestamp;
                    
                    // Only restore if saved within last 24 hours
                    if (timeDiff < 24 * 60 * 60 * 1000 && musicState.embedCode) {
                        console.log('🔄 Restoring music state:', musicState);
                        
                        const container = document.getElementById('musicContainer');
                        if (container && musicState.embedCode.includes('<iframe')) {
                            container.innerHTML = `<div class="music-iframe-container">${musicState.embedCode}</div>`;
                            showMusicStatus('🎵 Music restored from previous session!', 'success');
                            
                            // Auto-expand player if music was loaded
                            if (!musicPlayerVisible) {
                                toggleMusicPlayer();
                            }
                            
                            return true;
                        }
                    } else if (timeDiff >= 24 * 60 * 60 * 1000) {
                        // Clear old state
                        localStorage.removeItem('musicPlayerState');
                        console.log('🗑️ Old music state cleared');
                    }
                } catch (error) {
                    console.error('❌ Error loading music state:', error);
                    localStorage.removeItem('musicPlayerState');
                }
            }
            return false;
        }

        function loadMusic() {
            const input = document.getElementById('musicInput');
            const container = document.getElementById('musicContainer');
            let embedCode = input.value.trim();
            
            if (!embedCode) {
                showMusicStatus('⚠️ Vui lòng nhập embed code hoặc link!', 'error');
                return;
            }
            
            // Convert YouTube URL to embed
            if (embedCode.includes('youtube.com/watch') || embedCode.includes('youtu.be/')) {
                let videoId = '';
                if (embedCode.includes('youtube.com/watch')) {
                    videoId = embedCode.split('v=')[1]?.split('&')[0];
                } else if (embedCode.includes('youtu.be/')) {
                    videoId = embedCode.split('youtu.be/')[1]?.split('?')[0];
                }
                if (videoId) {
                    embedCode = `<iframe class="music-iframe" src="https://www.youtube.com/embed/${videoId}?autoplay=1&loop=1&playlist=${videoId}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
                }
            }
            
            // Convert Spotify URL to embed
            else if (embedCode.includes('open.spotify.com')) {
                const match = embedCode.match(/open\.spotify\.com\/(track|album|playlist)\/([a-zA-Z0-9]+)/);
                if (match) {
                    const [, type, id] = match;
                    embedCode = `<iframe class="music-iframe" src="https://open.spotify.com/embed/${type}/${id}" allowtransparency="true" allow="encrypted-media"></iframe>`;
                }
            }
            
            // Convert SoundCloud URL (basic support)
            else if (embedCode.includes('soundcloud.com')) {
                showMusicStatus('🔊 For SoundCloud, please use the embed code from the share menu', 'info');
                return;
            }
            
            // If it's already an iframe, use it directly
            if (embedCode.includes('<iframe')) {
                container.innerHTML = `<div class="music-iframe-container">${embedCode}</div>`;
                showMusicStatus('✅ Music loaded successfully! Enjoy your tunes! 🎵', 'success');
                
                // Save music state for cross-page persistence
                saveMusicState(embedCode, true);
                
                // Auto-minimize after loading music (optional)
                setTimeout(() => {
                    if (!isMinimized) {
                        showToast('💡 Tip: Click ➖ to minimize player and save space!', 'info');
                    }
                }, 2000);
                
                input.value = '';
            } else {
                showMusicStatus('❌ Invalid format! Please use embed code or supported link.', 'error');
            }
        }

        function clearMusic() {
            const container = document.getElementById('musicContainer');
            const input = document.getElementById('musicInput');
            const indicator = document.getElementById('musicIndicator');
            
            container.innerHTML = '';
            input.value = '';
            
            // Hide music indicator when clearing
            indicator.classList.remove('show');
            
            // Clear saved music state
            localStorage.removeItem('musicPlayerState');
            console.log('🗑️ Music cleared and state removed');
            
            showMusicStatus('🗑️ Music cleared!', 'info');
        }

        function useExample(element) {
            const link = element.getAttribute('data-link');
            document.getElementById('musicInput').value = link;
            showMusicStatus('📋 Example loaded! Click "Load Music" to play.', 'info');
        }

        // Enhanced toggle with state restoration
        function toggleMusicPlayer() {
            const musicSection = document.getElementById('musicInputSection');
            const musicStatus = document.getElementById('musicStatus');
            const toggleBtn = document.getElementById('musicToggleBtn');
            const player = document.querySelector('.music-player');
            
            musicPlayerVisible = !musicPlayerVisible;
            
            if (musicPlayerVisible) {
                musicSection.classList.add('active');
                musicStatus.style.display = 'none';
                toggleBtn.textContent = '✖️';
                player.classList.add('expanded');
                
                // Try to restore music state when opening player
                const restored = loadMusicState();
                if (!restored) {
                    showMusicStatus('🎵 Music player opened! Paste your music link below.', 'info');
                }
            } else {
                musicSection.classList.remove('active');
                musicStatus.style.display = 'block';
                musicStatus.textContent = 'Click 🎶 để mở music player';
                toggleBtn.textContent = '🎶';
                player.classList.remove('expanded');
            }
        }

        // Auto-restore music on page load
        function autoRestoreMusic() {
            console.log('🔍 Checking for saved music state...');
            
            const restored = loadMusicState();
            if (restored) {
                console.log('✅ Music auto-restored!');
                
                // Show music indicator if minimized
                if (isMinimized) {
                    const indicator = document.getElementById('musicIndicator');
                    indicator.classList.add('show');
                }
                
                // Show a brief notification
                setTimeout(() => {
                    const container = document.getElementById('musicContainer');
                    if (container && container.innerHTML.trim()) {
                        showToast('🎵 Music continues from where you left off!', 'success');
                    }
                }, 1000);
            } else {
                console.log('ℹ️ No music state to restore');
            }
        }

        // Allow Ctrl+Enter key to load music
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 DOM Content Loaded - Initializing music player...');
            
            const musicInput = document.getElementById('musicInput');
            if (musicInput) {
                musicInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && e.ctrlKey) {
                        loadMusic();
                    }
                });
                console.log('✅ Keyboard shortcuts initialized');
            }
            
            // Initialize draggable functionality with delay
            setTimeout(() => {
                makeDraggable();
                console.log('✅ Draggable initialized');
            }, 100);
            
            // Load saved position with delay
            setTimeout(() => {
                loadPlayerPosition();
                console.log('✅ Position loaded');
            }, 200);
            
            // Auto-restore music state
            setTimeout(() => {
                autoRestoreMusic();
                console.log('✅ Music state checked');
            }, 300);
            
            // Load minimize state
            setTimeout(() => {
                loadMinimizeState();
                console.log('✅ Minimize state loaded');
            }, 400);
            
            // Add reset button functionality
            setTimeout(() => {
                const musicHeader = document.getElementById('musicHeader');
                if (musicHeader) {
                    // Triple-click to reset position
                    let clickCount = 0;
                    musicHeader.addEventListener('click', function(e) {
                        clickCount++;
                        if (clickCount === 3) {
                            resetPosition();
                            clickCount = 0;
                        }
                        setTimeout(() => {
                            if (clickCount < 3) clickCount = 0;
                        }, 500);
                    });
                    console.log('✅ Triple-click reset added');
                }
            }, 300);
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (musicPlayer) {
                    const rect = musicPlayer.getBoundingClientRect();
                    
                    // If player is outside viewport after resize, move it back
                    if (rect.right > window.innerWidth || rect.bottom > window.innerHeight) {
                        resetPosition();
                        console.log('📐 Window resized - position reset');
                    }
                }
            });
            
            console.log('🎵 Music Player Pro fully initialized!');
            console.log('💡 Tips:');
            console.log('   - Click and drag the header area to move');
            console.log('   - Triple-click header to reset position');
            console.log('   - Click ➖ to minimize player and save space');
            console.log('   - Click ◀ button or 🎵 indicator to restore player');
            console.log('   - Music will persist across page changes');
            console.log('   - Music state auto-expires after 24 hours');
            console.log('   - Check browser console for debug info');
        });

        // Your existing scripts
        var hostUrl = "/assets/index.html";
    </script>

    <!-- Your existing scripts -->
    <script src="/assets/plugins/global/plugins.bundle.js"></script>
    <script src="/assets/js/fix-search-error.js"></script>
    <script src="/assets/js/scripts.bundle.js"></script>

</body>
</html>