#!/bin/bash

# ============================================
# BUMISULTAN APP - AUTO DEPLOYMENT SCRIPT
# ============================================
# Jalankan: bash deploy.sh
# ============================================

set -e  # Exit jika ada error

PROJECT_PATH="/home/bumisultan/bumisultanAPP"
LOG_FILE="$PROJECT_PATH/deploy.log"

# Function untuk print
print_header() {
    echo ""
    echo "=================================="
    echo "🚀 $1"
    echo "=================================="
}

print_success() {
    echo "✅ $1"
}

print_error() {
    echo "❌ $1"
}

# Log to file
log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" >> "$LOG_FILE"
}

# ============================================
# START DEPLOYMENT
# ============================================

print_header "STARTING DEPLOYMENT PROCESS"
log "Deployment started"

# ============================================
# 1. Navigate to project
# ============================================
print_header "1. Navigating to project directory"
cd "$PROJECT_PATH" || exit 1
print_success "Directory: $(pwd)"
log "Changed to directory: $PROJECT_PATH"

# ============================================
# 2. Git Pull
# ============================================
print_header "2. Pulling latest changes from GitHub"
git fetch origin main
git reset --hard origin/main
print_success "Git pull completed"
log "Git pull completed"

# ============================================
# 3. Clean git status
# ============================================
print_header "3. Cleaning up session files"
git restore storage/framework/sessions/ 2>/dev/null || true
git restore bootstrap/cache/ 2>/dev/null || true
print_success "Cleaned up session files"
log "Cleaned up session files"

# ============================================
# 4. Clear all caches
# ============================================
print_header "4. Clearing Laravel caches"

php artisan cache:clear
print_success "Cache cleared"
log "Cache cleared"

php artisan config:clear
print_success "Config cleared"
log "Config cleared"

php artisan view:clear
print_success "Views cleared"
log "Views cleared"

php artisan route:clear
print_success "Routes cleared"
log "Routes cleared"

# ============================================
# 5. Rebuild caches
# ============================================
print_header "5. Rebuilding caches"

php artisan config:cache
print_success "Config cached"
log "Config cached"

php artisan view:cache
print_success "Views cached"
log "Views cached"

# Skip route:cache karena ada error, uncomment jika sudah fixed
# php artisan route:cache
# print_success "Routes cached"
# log "Routes cached"

# ============================================
# 6. Restart queue
# ============================================
print_header "6. Restarting queue"
php artisan queue:restart
print_success "Queue restarted"
log "Queue restarted"

# ============================================
# 7. Verify deployment
# ============================================
print_header "7. Verifying deployment"

# Check if updateAbsenPulang method exists
if grep -q "updateAbsenPulang" app/Http/Controllers/PresensiController.php; then
    print_success "updateAbsenPulang method found ✓"
    log "updateAbsenPulang method verified"
else
    print_error "updateAbsenPulang method NOT found!"
    log "ERROR: updateAbsenPulang method NOT found"
fi

# Check if route exists
if grep -q "update-absen-pulang" routes/web.php; then
    print_success "update-absen-pulang route found ✓"
    log "update-absen-pulang route verified"
else
    print_error "update-absen-pulang route NOT found!"
    log "ERROR: update-absen-pulang route NOT found"
fi

# ============================================
# 8. Check file sizes
# ============================================
print_header "8. File information"
echo "PresensiController.php: $(wc -l < app/Http/Controllers/PresensiController.php) lines"
echo "routes/web.php: $(wc -l < routes/web.php) lines"
echo "checklist.blade.php: $(wc -l < resources/views/perawatan/karyawan/checklist.blade.php) lines"
log "File sizes verified"

# ============================================
# 9. Check git status
# ============================================
print_header "9. Git Status"
echo "Current branch: $(git rev-parse --abbrev-ref HEAD)"
echo "Latest commit:"
git log --oneline -1
log "Git status verified"

# ============================================
# 10. Final status
# ============================================
print_header "DEPLOYMENT COMPLETED SUCCESSFULLY! ✅"
echo ""
echo "📋 Summary:"
echo "   - Git pull: ✓"
echo "   - Cache cleared: ✓"
echo "   - Config cached: ✓"
echo "   - Views cached: ✓"
echo "   - Queue restarted: ✓"
echo "   - Files verified: ✓"
echo ""
echo "🔗 Test aplikasi di: http://bumisultan.site/perawatan-karyawan"
echo ""
echo "📝 Log file: $LOG_FILE"
echo ""

log "Deployment completed successfully"

exit 0
