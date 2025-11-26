/**
 * Game Boy Wars 3 - Client-side Map Renderer
 *
 * Renders maps on canvas with terrain and unit layers
 * Supports hover tooltips for tile/unit information
 */

class GBWarsMapRenderer {
    constructor(options = {}) {
        // Display tile size (can be scaled: 16 = 1x, 32 = 2x)
        this.tileSize = options.tileSize || 16;
        this.staggerOffset = this.tileSize / 2;

        // Source tileset sizes (fixed based on tileset images)
        this.terrainSrcSize = 16;  // terrain_16x16.png uses 16px tiles
        this.unitSrcSize = 16;     // units_16x16.png uses 16px tiles

        // Tileset images
        this.terrainTileset = null;
        this.unitTileset = null;
        this.tilesetsLoaded = false;

        // Map data
        this.mapData = null;

        // Canvas elements
        this.container = null;
        this.terrainCanvas = null;
        this.unitCanvas = null;
        this.overlayCanvas = null;

        // Tooltip element
        this.tooltip = null;

        // Hover state
        this.hoveredTile = null;

        // Localized names (loaded from API)
        this.terrainNames = {};
        this.unitNames = {};
        this.factionNames = {};
        this.stringsLoaded = false;
    }

    /**
     * Load localized strings from API
     *
     * @param {string} lang - Language code (default: 'en')
     * @param {string} apiUrl - Base URL for the strings API
     */
    async loadStrings(lang = 'en', apiUrl = 'api/strings.php') {
        try {
            const response = await fetch(`${apiUrl}?lang=${encodeURIComponent(lang)}`);
            if (!response.ok) {
                throw new Error('Failed to load strings');
            }

            const data = await response.json();

            // Handle both array format (sequential keys) and object format
            if (Array.isArray(data.terrain)) {
                this.terrainNames = {};
                data.terrain.forEach((value, index) => {
                    this.terrainNames[index] = value;
                });
            } else {
                this.terrainNames = {};
                for (const [key, value] of Object.entries(data.terrain || {})) {
                    this.terrainNames[parseInt(key)] = value;
                }
            }

            if (Array.isArray(data.units)) {
                this.unitNames = {};
                data.units.forEach((value, index) => {
                    this.unitNames[index] = value;
                });
            } else {
                this.unitNames = {};
                for (const [key, value] of Object.entries(data.units || {})) {
                    this.unitNames[parseInt(key)] = value;
                }
            }

            this.factionNames = data.factions || {};
            this.stringsLoaded = true;

        } catch (e) {
            console.warn('Failed to load localized strings, using fallbacks:', e);
            this.loadFallbackNames();
        }
    }

    /**
     * Load fallback names if API fails
     */
    loadFallbackNames() {
        // Minimal fallback - just show IDs
        this.terrainNames = {};
        this.unitNames = {};
        this.factionNames = { rs: 'Red Star', wm: 'White Moon', neutral: 'Neutral' };
        this.stringsLoaded = true;
    }

    /**
     * Get terrain name with fallback
     */
    getTerrainName(terrainId) {
        return this.terrainNames[terrainId] || `Terrain 0x${terrainId.toString(16).toUpperCase().padStart(2, '0')}`;
    }

    /**
     * Get unit name with fallback
     */
    getUnitName(unitId) {
        return this.unitNames[unitId] || `Unit 0x${unitId.toString(16).toUpperCase().padStart(2, '0')}`;
    }

    /**
     * Load tileset images
     */
    async loadTilesets(terrainUrl, unitUrl) {
        const loadImage = (url) => {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.onload = () => resolve(img);
                img.onerror = reject;
                img.src = url;
            });
        };

        try {
            [this.terrainTileset, this.unitTileset] = await Promise.all([
                loadImage(terrainUrl),
                loadImage(unitUrl)
            ]);
            this.tilesetsLoaded = true;
        } catch (e) {
            console.error('Failed to load tilesets:', e);
            throw e;
        }
    }

    /**
     * Initialize the renderer with a container element
     */
    init(container) {
        this.container = container;
        this.container.style.position = 'relative';

        // Create canvas layers
        this.terrainCanvas = this.createCanvas('terrain-layer');
        this.unitCanvas = this.createCanvas('unit-layer');
        this.overlayCanvas = this.createCanvas('overlay-layer');

        // Create tooltip
        this.tooltip = document.createElement('div');
        this.tooltip.className = 'map-tooltip';
        this.tooltip.style.cssText = `
            position: absolute;
            display: none;
            background: rgba(0, 0, 0, 0.85);
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 13px;
            pointer-events: none;
            z-index: 1000;
            white-space: nowrap;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        `;
        this.container.appendChild(this.tooltip);

        // Add event listeners
        this.overlayCanvas.addEventListener('mousemove', (e) => this.handleMouseMove(e));
        this.overlayCanvas.addEventListener('mouseleave', () => this.hideTooltip());
    }

    /**
     * Create a canvas element
     */
    createCanvas(className) {
        const canvas = document.createElement('canvas');
        canvas.className = className;
        canvas.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            image-rendering: pixelated;
            image-rendering: crisp-edges;
        `;
        this.container.appendChild(canvas);
        return canvas;
    }

    /**
     * Set map data and render
     */
    setMapData(mapData) {
        this.mapData = mapData;

        // Build unit lookup map for quick access
        this.unitMap = new Map();
        for (const unit of mapData.units) {
            const key = `${unit.x},${unit.y}`;
            this.unitMap.set(key, unit);
        }

        this.render();
    }

    /**
     * Render the map
     */
    render() {
        if (!this.tilesetsLoaded || !this.mapData) {
            console.warn('Cannot render: tilesets or map data not loaded');
            return;
        }

        const { width, height } = this.mapData;
        const canvasWidth = width * this.tileSize + this.staggerOffset;
        const canvasHeight = height * this.tileSize;

        // Set container size
        this.container.style.width = canvasWidth + 'px';
        this.container.style.height = canvasHeight + 'px';

        // Size all canvases
        [this.terrainCanvas, this.unitCanvas, this.overlayCanvas].forEach(canvas => {
            canvas.width = canvasWidth;
            canvas.height = canvasHeight;
            canvas.style.width = canvasWidth + 'px';
            canvas.style.height = canvasHeight + 'px';
        });

        this.renderTerrain();
        this.renderUnits();
    }

    /**
     * Render terrain layer
     */
    renderTerrain() {
        const ctx = this.terrainCanvas.getContext('2d');
        const { width, height, tiles } = this.mapData;

        // Disable image smoothing for crisp pixel art
        ctx.imageSmoothingEnabled = false;

        // Fill with sea color for edges
        ctx.fillStyle = '#0070C0';
        ctx.fillRect(0, 0, this.terrainCanvas.width, this.terrainCanvas.height);

        // Render tiles
        for (let y = 0; y < height; y++) {
            const rowOffset = (y % 2 === 1) ? this.staggerOffset : 0;

            for (let x = 0; x < width; x++) {
                const tileIndex = y * width + x;
                let tileId = tiles[tileIndex] || 0x20;

                // Clamp to valid range
                if (tileId > 0x2A) tileId = 0x20;

                const [srcX, srcY] = this.getTileCoords(tileId);
                const destX = x * this.tileSize + rowOffset;
                const destY = y * this.tileSize;

                // Draw from source size, scale to display size
                ctx.drawImage(
                    this.terrainTileset,
                    srcX, srcY, this.terrainSrcSize, this.terrainSrcSize,
                    destX, destY, this.tileSize, this.tileSize
                );
            }
        }
    }

    /**
     * Render units layer
     */
    renderUnits() {
        const ctx = this.unitCanvas.getContext('2d');
        ctx.clearRect(0, 0, this.unitCanvas.width, this.unitCanvas.height);

        // Disable image smoothing for crisp pixel art
        ctx.imageSmoothingEnabled = false;

        for (const unit of this.mapData.units) {
            const rowOffset = (unit.y % 2 === 1) ? this.staggerOffset : 0;
            const destX = unit.x * this.tileSize + rowOffset;
            const destY = unit.y * this.tileSize;

            const [srcX, srcY] = this.getUnitCoords(unit.type);

            // Draw from source size, scale to display size
            ctx.drawImage(
                this.unitTileset,
                srcX, srcY, this.unitSrcSize, this.unitSrcSize,
                destX, destY, this.tileSize, this.tileSize
            );
        }
    }

    /**
     * Get tile source coordinates in tileset (8 tiles per row)
     * Uses terrainSrcSize for source tileset lookup
     */
    getTileCoords(tileId) {
        const tilesPerRow = 8;
        const x = (tileId % tilesPerRow) * this.terrainSrcSize;
        const y = Math.floor(tileId / tilesPerRow) * this.terrainSrcSize;
        return [x, y];
    }

    /**
     * Get unit source coordinates in tileset
     * Unit ID maps directly to tile index (0x00=tile 0, 0x02=tile 2, etc.)
     * Uses unitSrcSize for source tileset lookup
     */
    getUnitCoords(unitId) {
        const tilesPerRow = 8;
        const x = (unitId % tilesPerRow) * this.unitSrcSize;
        const y = Math.floor(unitId / tilesPerRow) * this.unitSrcSize;
        return [x, y];
    }

    /**
     * Convert pixel coordinates to tile coordinates
     */
    pixelToTile(pixelX, pixelY) {
        // Account for stagger
        const tileY = Math.floor(pixelY / this.tileSize);
        const rowOffset = (tileY % 2 === 1) ? this.staggerOffset : 0;
        const tileX = Math.floor((pixelX - rowOffset) / this.tileSize);

        // Bounds check
        if (tileX < 0 || tileX >= this.mapData.width ||
            tileY < 0 || tileY >= this.mapData.height) {
            return null;
        }

        return { x: tileX, y: tileY };
    }

    /**
     * Handle mouse movement
     */
    handleMouseMove(event) {
        const rect = this.overlayCanvas.getBoundingClientRect();
        const pixelX = event.clientX - rect.left;
        const pixelY = event.clientY - rect.top;

        const tile = this.pixelToTile(pixelX, pixelY);

        if (!tile) {
            this.hideTooltip();
            this.clearHighlight();
            return;
        }

        // Check if tile changed
        if (this.hoveredTile &&
            this.hoveredTile.x === tile.x &&
            this.hoveredTile.y === tile.y) {
            // Just update tooltip position
            this.positionTooltip(event.clientX, event.clientY);
            return;
        }

        this.hoveredTile = tile;
        this.highlightTile(tile);
        this.showTooltip(tile, event.clientX, event.clientY);
    }

    /**
     * Highlight a tile
     */
    highlightTile(tile) {
        const ctx = this.overlayCanvas.getContext('2d');
        ctx.clearRect(0, 0, this.overlayCanvas.width, this.overlayCanvas.height);

        const rowOffset = (tile.y % 2 === 1) ? this.staggerOffset : 0;
        const x = tile.x * this.tileSize + rowOffset;
        const y = tile.y * this.tileSize;

        // Draw highlight rectangle
        ctx.strokeStyle = 'rgba(255, 255, 255, 0.8)';
        ctx.lineWidth = 2;
        ctx.strokeRect(x + 1, y + 1, this.tileSize - 2, this.tileSize - 2);

        // Inner glow
        ctx.strokeStyle = 'rgba(255, 255, 0, 0.5)';
        ctx.lineWidth = 1;
        ctx.strokeRect(x + 2, y + 2, this.tileSize - 4, this.tileSize - 4);
    }

    /**
     * Clear highlight
     */
    clearHighlight() {
        const ctx = this.overlayCanvas.getContext('2d');
        ctx.clearRect(0, 0, this.overlayCanvas.width, this.overlayCanvas.height);
        this.hoveredTile = null;
    }

    /**
     * Show tooltip with tile info
     */
    showTooltip(tile, mouseX, mouseY) {
        const tileIndex = tile.y * this.mapData.width + tile.x;
        const terrainId = this.mapData.tiles[tileIndex] || 0x20;
        const terrainName = this.getTerrainName(terrainId);

        // Check for unit at this position
        const unitKey = `${tile.x},${tile.y}`;
        const unit = this.unitMap.get(unitKey);

        let html = `
            <div class="tooltip-coords">Position: (${tile.x}, ${tile.y})</div>
            <div class="tooltip-terrain">Terrain: ${terrainName}</div>
        `;

        if (unit) {
            const unitName = this.getUnitName(unit.type);
            html += `<div class="tooltip-unit" style="color: #ffcc00;">Unit: ${unitName}</div>`;
        }

        this.tooltip.innerHTML = html;
        this.tooltip.style.display = 'block';
        this.positionTooltip(mouseX, mouseY);
    }

    /**
     * Position tooltip near cursor
     */
    positionTooltip(mouseX, mouseY) {
        const rect = this.container.getBoundingClientRect();
        const tooltipRect = this.tooltip.getBoundingClientRect();

        let x = mouseX - rect.left + 15;
        let y = mouseY - rect.top + 15;

        // Keep tooltip in bounds
        if (x + tooltipRect.width > this.container.offsetWidth) {
            x = mouseX - rect.left - tooltipRect.width - 15;
        }
        if (y + tooltipRect.height > this.container.offsetHeight) {
            y = mouseY - rect.top - tooltipRect.height - 15;
        }

        this.tooltip.style.left = x + 'px';
        this.tooltip.style.top = y + 'px';
    }

    /**
     * Hide tooltip
     */
    hideTooltip() {
        this.tooltip.style.display = 'none';
        this.clearHighlight();
    }

    /**
     * Set zoom level
     */
    setZoom(tileSize) {
        this.tileSize = tileSize;
        this.staggerOffset = tileSize / 2;
        if (this.mapData) {
            this.render();
        }
    }
}

// Export for use
window.GBWarsMapRenderer = GBWarsMapRenderer;
