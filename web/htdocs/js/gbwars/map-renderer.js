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
        this.cursorSrcSize = 24;   // cursor.png uses 22px tiles
        this.waterSrcSize = 16;    // water.png uses 16px tiles

        // Tileset images
        this.terrainTileset = null;
        this.unitTileset = null;
        this.cursorTileset = null;
        this.waterTileset = null;
        this.tilesetsLoaded = false;

        // Animation settings
        this.waterAnimationFrame = 0;
        this.cursorAnimationFrame = 0;
        this.waterAnimationInterval = null;
        this.cursorAnimationInterval = null;

        // Water animation: 3 frames, 4 terrain types
        this.waterAnimationSpeed = 450; // ms per frame
        this.waterAnimationFrames = 3;
        this.waterTileRows = {
            0x2A: 0,  // Shoal -> row 0
            0x29: 1,  // Sea -> row 1
            0x28: 2,  // River -> row 2
            0x22: 3,  // Bridge -> row 3
            0x23: 3,  // Bridge variant -> row 3
        };

        // Cursor animation: 2 frames, 2 types (hover and select)
        this.cursorAnimationSpeed = 250; // ms per frame
        this.cursorAnimationFrames = 2;
        this.cursorRows = {
            hover: 0,   // Top row
            select: 1,  // Bottom row
        };

        // Map data
        this.mapData = null;

        // Canvas elements
        this.container = null;
        this.terrainCanvas = null;
        this.unitCanvas = null;
        this.overlayCanvas = null;

        // Tooltip element
        this.tooltip = null;

        // Hover/selection state
        this.hoveredTile = null;
        this.selectedTile = null;

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
    async loadTilesets(terrainUrl, unitUrl, cursorUrl, waterUrl) {
        const loadImage = (url) => {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.onload = () => resolve(img);
                img.onerror = reject;
                img.src = url;
            });
        };

        try {
            [this.terrainTileset, this.unitTileset, this.cursorTileset, this.waterTileset] = await Promise.all([
                loadImage(terrainUrl),
                loadImage(unitUrl),
                loadImage(cursorUrl),
                loadImage(waterUrl)
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

        // Create canvas layers (order matters: terrain, water, units, overlay)
        this.terrainCanvas = this.createCanvas('terrain-layer');
        this.waterCanvas = this.createCanvas('water-layer');
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
        this.overlayCanvas.addEventListener('click', (e) => this.handleClick(e));
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
        [this.terrainCanvas, this.waterCanvas, this.unitCanvas, this.overlayCanvas].forEach(canvas => {
            canvas.width = canvasWidth;
            canvas.height = canvasHeight;
            canvas.style.width = canvasWidth + 'px';
            canvas.style.height = canvasHeight + 'px';
        });

        this.renderTerrain();
        this.renderWater();
        this.renderUnits();
        this.startAnimations();
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
     * Render water animation layer
     */
    renderWater() {
        const ctx = this.waterCanvas.getContext('2d');
        ctx.clearRect(0, 0, this.waterCanvas.width, this.waterCanvas.height);
        ctx.imageSmoothingEnabled = false;

        const { width, height, tiles } = this.mapData;

        for (let y = 0; y < height; y++) {
            const rowOffset = (y % 2 === 1) ? this.staggerOffset : 0;

            for (let x = 0; x < width; x++) {
                const tileIndex = y * width + x;
                const tileId = tiles[tileIndex] || 0x20;

                // Check if this is a water tile
                if (tileId in this.waterTileRows) {
                    const row = this.waterTileRows[tileId];
                    const srcX = this.waterAnimationFrame * this.waterSrcSize;
                    const srcY = row * this.waterSrcSize;
                    const destX = x * this.tileSize + rowOffset;
                    const destY = y * this.tileSize;

                    ctx.drawImage(
                        this.waterTileset,
                        srcX, srcY, this.waterSrcSize, this.waterSrcSize,
                        destX, destY, this.tileSize, this.tileSize
                    );
                }
            }
        }
    }

    /**
     * Start animation loops (water and cursor run independently)
     */
    startAnimations() {
        this.stopAnimations();

        // Water animation
        this.waterAnimationInterval = setInterval(() => {
            this.waterAnimationFrame = (this.waterAnimationFrame + 1) % this.waterAnimationFrames;
            this.renderWater();
        }, this.waterAnimationSpeed);

        // Cursor animation
        this.cursorAnimationInterval = setInterval(() => {
            this.cursorAnimationFrame = (this.cursorAnimationFrame + 1) % this.cursorAnimationFrames;
            this.renderOverlay();
        }, this.cursorAnimationSpeed);
    }

    /**
     * Stop animations
     */
    stopAnimations() {
        if (this.waterAnimationInterval) {
            clearInterval(this.waterAnimationInterval);
            this.waterAnimationInterval = null;
        }
        if (this.cursorAnimationInterval) {
            clearInterval(this.cursorAnimationInterval);
            this.cursorAnimationInterval = null;
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
     * Handle click on map
     */
    handleClick(event) {
        const rect = this.overlayCanvas.getBoundingClientRect();
        const pixelX = event.clientX - rect.left;
        const pixelY = event.clientY - rect.top;

        const tile = this.pixelToTile(pixelX, pixelY);

        if (!tile) {
            this.selectedTile = null;
            this.renderOverlay();
            return;
        }

        // Toggle selection
        if (this.selectedTile &&
            this.selectedTile.x === tile.x &&
            this.selectedTile.y === tile.y) {
            this.selectedTile = null;
        } else {
            this.selectedTile = tile;
        }

        this.renderOverlay();
    }

    /**
     * Render overlay (cursor for hovered and selected tiles)
     */
    renderOverlay() {
        const ctx = this.overlayCanvas.getContext('2d');
        ctx.clearRect(0, 0, this.overlayCanvas.width, this.overlayCanvas.height);
        ctx.imageSmoothingEnabled = false;

        // Draw selected tile cursor
        if (this.selectedTile) {
            this.drawCursor(ctx, this.selectedTile, this.cursorRows.select, this.cursorAnimationFrame);
        }

        // Draw hovered tile cursor
        if (this.hoveredTile) {
            // Don't draw hover cursor if it's the same as selected
            if (!this.selectedTile ||
                this.hoveredTile.x !== this.selectedTile.x ||
                this.hoveredTile.y !== this.selectedTile.y) {
                this.drawCursor(ctx, this.hoveredTile, this.cursorRows.hover, this.cursorAnimationFrame);
            }
        }
    }

    /**
     * Draw cursor sprite at tile position
     * @param {CanvasRenderingContext2D} ctx
     * @param {{x: number, y: number}} tile
     * @param {number} cursorRow - 0 for hover, 1 for selected
     * @param {number} cursorFrame - animation frame (0 or 1)
     */
    drawCursor(ctx, tile, cursorRow, cursorFrame) {
        const rowOffset = (tile.y % 2 === 1) ? this.staggerOffset : 0;

        // Center the cursor on the tile (cursor is larger than tile)
        const cursorScale = this.tileSize / this.terrainSrcSize;
        const cursorDisplaySize = this.cursorSrcSize * cursorScale;
        const offset = (cursorDisplaySize - this.tileSize) / 2;

        const destX = tile.x * this.tileSize + rowOffset - offset;
        const destY = tile.y * this.tileSize - offset;

        const srcX = cursorFrame * this.cursorSrcSize;
        const srcY = cursorRow * this.cursorSrcSize;

        ctx.drawImage(
            this.cursorTileset,
            srcX, srcY, this.cursorSrcSize, this.cursorSrcSize,
            destX, destY, cursorDisplaySize, cursorDisplaySize
        );
    }

    /**
     * Highlight a tile (legacy method, now calls renderOverlay)
     */
    highlightTile() {
        this.renderOverlay();
    }

    /**
     * Clear highlight
     */
    clearHighlight() {
        this.hoveredTile = null;
        this.renderOverlay();
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
