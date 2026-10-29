// Order Assign & Status Logic (Supports up to 5 workers)

function initOrderAssign() {
    const MAX_WORKERS = 5;

    // --- Elements ---
    const workerListEl = document.getElementById('workerList');
    const dropZoneEl = document.getElementById('dropZone');
    const workerIdInput = document.getElementById('workerIdInput');
    const workerHiddenInputs = document.getElementById('workerHiddenInputs');
    const assignForm = document.getElementById('assignForm');
    const workerSearch = document.getElementById('workerSearch');
    const dropPlaceholder = document.getElementById('dropPlaceholder') || (dropZoneEl ? dropZoneEl.querySelector('.drop-placeholder') : null);
    const assignedContainer = document.getElementById('assignedWorkersContainer');
    const dropSlotHint = document.getElementById('dropSlotHint');
    const assignedCountEl = document.getElementById('assignedCount');
    const slotsLeftEl = document.getElementById('slotsLeft');
    const workerCountBadge = document.getElementById('workerCountBadge');

    if (!assignForm) return;

    let assignedWorkers = [];
    let submitTimer = null;

    // ===== Parse Initial State =====
    try {
        if (assignForm.dataset.initialWorkers) {
            const rawData = assignForm.dataset.initialWorkers.trim();
            if (rawData) {
                const parsed = JSON.parse(rawData);
                if (Array.isArray(parsed)) {
                    assignedWorkers = parsed.map(function (w) {
                        return { id: String(w.id || w.worker_id), name: w.name || w.username || 'Pekerja' };
                    });
                }
            }
        }
    } catch (e) {
        console.warn('Initial workers JSON parse warning, falling back to DOM:', e);
    }

    // Fallback 1: Scrape from pre-rendered DOM rows in assignedWorkersContainer
    if (assignedWorkers.length === 0 && assignedContainer) {
        const rows = assignedContainer.querySelectorAll('.assigned-worker-row');
        rows.forEach(function (r) {
            const id = r.dataset.id;
            const nameEl = r.querySelector('[style*="font-weight: 600"]');
            const name = (nameEl ? nameEl.textContent.trim() : '') || r.dataset.name || 'Pekerja';
            if (id && !assignedWorkers.some(function (w) { return String(w.id) === String(id); })) {
                assignedWorkers.push({ id: String(id), name: name });
            }
        });
    }

    // Fallback 2: Check hidden inputs (worker_ids[])
    if (assignedWorkers.length === 0 && workerHiddenInputs) {
        const hiddenInps = workerHiddenInputs.querySelectorAll('input[name="worker_ids[]"]');
        hiddenInps.forEach(function (inp) {
            const id = inp.value;
            if (id && !assignedWorkers.some(function (w) { return String(w.id) === String(id); })) {
                const chip = workerListEl ? workerListEl.querySelector('.worker-chip[data-id="' + id + '"]') : null;
                const name = chip ? (chip.dataset.name || chip.textContent.trim()) : 'Pekerja';
                assignedWorkers.push({ id: String(id), name: name });
            }
        });
    }

    // Fallback 3: Fallback if still empty but workerIdInput has value
    if (assignedWorkers.length === 0 && workerIdInput && workerIdInput.value) {
        const fallbackName = assignForm.dataset.workerName || 'Assigned';
        assignedWorkers.push({ id: String(workerIdInput.value), name: fallbackName });
    }

    // ===== AJAX Silent Submit =====
    function submitSilent(form, onSuccess) {
        if (submitTimer) clearTimeout(submitTimer);
        submitTimer = setTimeout(function () {
            const formData = new FormData(form);
            fetch(form.action, {
                method: form.method || 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(function (res) {
                    return res.text();
                })
                .then(function () {
                    showToast('Penugasan tersimpan', 'success');
                    if (typeof onSuccess === 'function') onSuccess();
                })
                .catch(function () {
                    showToast('Gagal menyimpan penugasan', 'error');
                });
        }, 300);
    }

    function showToast(msg, type) {
        var color = type === 'success' ? '#2ecc71' : '#e74c3c';
        if (typeof Toastify !== 'undefined') {
            Toastify({
                text: msg,
                duration: 2500,
                gravity: 'top',
                position: 'right',
                style: {
                    background: '#fff',
                    color: '#333',
                    borderLeft: '5px solid ' + color,
                    borderRadius: '8px',
                    boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
                    padding: '12px 18px',
                    fontWeight: '500',
                    fontSize: '0.88rem'
                }
            }).showToast();
        }
    }

    // ===== Render State =====
    function renderAssignedWorkers() {
        const count = assignedWorkers.length;

        // Update counter & badge
        if (assignedCountEl) assignedCountEl.textContent = count;
        if (slotsLeftEl) slotsLeftEl.textContent = Math.max(0, MAX_WORKERS - count);

        if (workerCountBadge) {
            if (count >= MAX_WORKERS) {
                workerCountBadge.style.background = 'rgba(239, 68, 68, 0.1)';
                workerCountBadge.style.color = '#ef4444';
            } else if (count > 0) {
                workerCountBadge.style.background = 'rgba(34, 197, 94, 0.1)';
                workerCountBadge.style.color = '#16a34a';
            } else {
                workerCountBadge.style.background = 'var(--bg-input)';
                workerCountBadge.style.color = 'var(--text-muted)';
            }
        }

        // Drop zone UI visibility
        if (count === 0) {
            if (dropPlaceholder) dropPlaceholder.style.display = 'block';
            if (assignedContainer) {
                assignedContainer.style.display = 'none';
                assignedContainer.innerHTML = '';
            }
            if (dropSlotHint) dropSlotHint.style.display = 'none';
        } else {
            if (dropPlaceholder) dropPlaceholder.style.display = 'none';
            if (assignedContainer) {
                assignedContainer.style.display = 'flex';
                assignedContainer.innerHTML = '';

                assignedWorkers.forEach(function (worker, idx) {
                    const row = document.createElement('div');
                    row.className = 'assigned-worker-row';
                    row.dataset.id = worker.id;

                    const initials = worker.name ? worker.name.substring(0, 2).toUpperCase() : '??';

                    row.innerHTML = `
                        <div style="display: flex; align-items: center; gap: 10px; min-width: 0;">
                            <div style="width: 32px; height: 32px; background: var(--primary); color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; flex-shrink: 0;">
                                ${escapeHtml(initials)}
                            </div>
                            <div style="min-width: 0; text-align: left;">
                                <div style="font-weight: 600; font-size: 0.86rem; color: var(--text); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    ${escapeHtml(worker.name)}
                                </div>
                                <div style="font-size: 0.72rem; color: var(--text-muted); line-height: 1.1;">Stylist #${idx + 1}</div>
                            </div>
                        </div>
                        <button type="button" class="btn-remove-worker" onclick="window.removeWorker('${escapeHtml(worker.id)}')" title="Hapus pekerja ini">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    `;
                    assignedContainer.appendChild(row);
                });
            }

            // Slot hint if not maxed out
            if (dropSlotHint) {
                dropSlotHint.style.display = count < MAX_WORKERS ? 'block' : 'none';
            }
        }

        // Sync hidden inputs in assignForm
        if (workerHiddenInputs) {
            workerHiddenInputs.innerHTML = '';
            assignedWorkers.forEach(function (w) {
                const hiddenInp = document.createElement('input');
                hiddenInp.type = 'hidden';
                hiddenInp.name = 'worker_ids[]';
                hiddenInp.value = w.id;
                workerHiddenInputs.appendChild(hiddenInp);
            });
        }

        if (workerIdInput) {
            // Keep first worker id for backwards compatibility
            workerIdInput.value = assignedWorkers[0] ? assignedWorkers[0].id : '';
        }

        const allNames = assignedWorkers.map(function (w) { return w.name; }).join(', ');
        assignForm.dataset.workerName = allNames;

        // Also update any legacy displays if present
        const selectedAvatar = document.getElementById('selectedAvatar');
        const selectedName = document.getElementById('selectedName');
        if (selectedName) selectedName.textContent = allNames || 'Belum ada pekerja';
        if (selectedAvatar) {
            selectedAvatar.textContent = assignedWorkers[0] ? assignedWorkers[0].name.substring(0, 2).toUpperCase() : '??';
        }

        // Update modal display if present
        const modalDisplay = document.getElementById('modalWorkerDisplay');
        if (modalDisplay) {
            modalDisplay.textContent = allNames || '-';
        }

        // Highlight chips in workerList
        if (workerListEl) {
            workerListEl.querySelectorAll('.worker-chip').forEach(function (chip) {
                const isSelected = assignedWorkers.some(function (w) { return String(w.id) === String(chip.dataset.id); });
                if (isSelected) {
                    chip.classList.add('is-assigned');
                } else {
                    chip.classList.remove('is-assigned');
                }
            });
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, function (m) { return map[m]; });
    }

    // ===== Add & Remove Operations =====
    function addWorker(id, name) {
        if (!id) return false;
        id = String(id);

        if (assignedWorkers.length >= MAX_WORKERS) {
            showToast('Maksimal 5 pekerja yang dapat ditugaskan', 'error');
            return false;
        }

        const alreadyExists = assignedWorkers.some(function (w) { return String(w.id) === id; });
        if (alreadyExists) {
            showToast('Pekerja ' + (name || '') + ' sudah ditugaskan', 'error');
            return false;
        }

        assignedWorkers.push({ id: id, name: name || 'Pekerja' });
        renderAssignedWorkers();
        submitSilent(assignForm);
        return true;
    }

    function removeWorker(id) {
        id = String(id);
        const prevCount = assignedWorkers.length;
        assignedWorkers = assignedWorkers.filter(function (w) { return String(w.id) !== id; });

        if (assignedWorkers.length !== prevCount) {
            renderAssignedWorkers();
            submitSilent(assignForm);
            showToast('Pekerja dihapus', 'success');
        }
    }

    function resetWorkers() {
        if (assignedWorkers.length === 0) return;
        assignedWorkers = [];
        renderAssignedWorkers();
        submitSilent(assignForm);
        showToast('Semua penugasan pekerja dihapus', 'success');
    }

    // Expose functions globally for onclick events and modal sync
    window.addWorker = addWorker;
    window.removeWorker = removeWorker;
    window.resetWorker = resetWorkers;
    window.resetWorkers = resetWorkers;
    window.getAssignedWorkers = function () { return assignedWorkers; };
    window.getAssignedWorkerNames = function () {
        return assignedWorkers.map(function (w) { return w.name; }).join(', ');
    };

    // ===== SortableJS (Drag & Drop) =====
    if (workerListEl && dropZoneEl && typeof Sortable !== 'undefined') {
        new Sortable(workerListEl, {
            group: { name: 'workers', pull: 'clone', put: false },
            sort: false,
            animation: 150,
            ghostClass: 'sortable-ghost'
        });

        new Sortable(dropZoneEl, {
            group: 'workers',
            draggable: '.assigned-worker-row',
            filter: '#dropPlaceholder, #dropSlotHint, #assignedWorkersContainer, .drop-placeholder',
            preventOnFilter: false,
            animation: 150,
            ghostClass: 'sortable-ghost',
            onAdd: function (evt) {
                var item = evt.item;
                var id = item.dataset.id;
                var name = item.dataset.name;

                if (item.parentNode) {
                    item.parentNode.removeChild(item);
                }

                if (id) {
                    addWorker(id, name);
                }
            }
        });
    }

    // ===== Click to Add on Worker Chip =====
    if (workerListEl) {
        workerListEl.addEventListener('click', function (e) {
            var chip = e.target.closest('.worker-chip');
            if (chip && chip.dataset.id) {
                addWorker(chip.dataset.id, chip.dataset.name);
            }
        });
    }

    // ===== Worker Search Filter =====
    if (workerSearch) {
        workerSearch.addEventListener('input', function () {
            var term = this.value.toLowerCase().trim();
            document.querySelectorAll('.worker-chip').forEach(function (card) {
                var name = (card.dataset.name || '').toLowerCase();
                card.style.display = name.includes(term) ? 'flex' : 'none';
            });
        });
    }

    // Initial render
    renderAssignedWorkers();
}

// Auto Initialize
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initOrderAssign);
} else {
    initOrderAssign();
}
