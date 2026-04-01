/**
 * FrenchVerbs - Application JavaScript
 * Interactivité jQuery pour les cartes de conjugaison
 */

$(document).ready(function () {
    // ========================================
    // THEME TOGGLE (Light/Dark Mode)
    // ========================================
    const initTheme = () => {
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        updateThemeIcon(savedTheme);
    };

    const updateThemeIcon = (theme) => {
        const $icon = $('#theme-toggle i');
        if (theme === 'dark') {
            $icon.removeClass('ph-moon').addClass('ph-sun');
        } else {
            $icon.removeClass('ph-sun').addClass('ph-moon');
        }
    };

    $('#theme-toggle').on('click', function () {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });

    initTheme();

    const initNavbarToggle = () => {
        const $toggle = $('#navbar-toggle');
        const $main = $('#navbar-main');
        if (!$toggle.length || !$main.length) return;

        const setOpen = (open) => {
            $main.toggleClass('is-open', open);
            $toggle.attr('aria-expanded', open ? 'true' : 'false');
            $toggle.attr('aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu');
            $toggle.attr('title', open ? 'Fermer' : 'Menu');

            const $icon = $toggle.find('i').first();
            if ($icon.length) {
                $icon.toggleClass('ph-list', !open);
                $icon.toggleClass('ph-x', open);
            }
        };

        $toggle.on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            setOpen(!$main.hasClass('is-open'));
        });

        $(document).on('click', function (e) {
            if (!$main.hasClass('is-open')) return;
            if ($(e.target).closest('.navbar').length) return;
            setOpen(false);
        });

        $(document).on('keydown', function (e) {
            if (e.key !== 'Escape') return;
            setOpen(false);
        });

        $(document).on('click', '#navbar-main .nav-link, #navbar-main .navbar-create', function () {
            if (!window.matchMedia || !window.matchMedia('(max-width: 768px)').matches) return;
            setOpen(false);
        });

        $(window).on('resize', function () {
            if (!window.matchMedia || !window.matchMedia('(min-width: 769px)').matches) return;
            setOpen(false);
        });
    };

    const initDropdowns = () => {
        const setDropdownOpen = ($dropdown, open) => {
            $dropdown.toggleClass('is-open', open);
            const $toggle = $dropdown.find('.dropdown-toggle').first();
            if ($toggle.length) $toggle.attr('aria-expanded', open ? 'true' : 'false');
        };

        const closeAll = () => {
            $('[data-dropdown].is-open').each(function () {
                setDropdownOpen($(this), false);
            });
        };

        $(document).on('click', '[data-dropdown] .dropdown-toggle', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const $dropdown = $(this).closest('[data-dropdown]');
            const shouldOpen = !$dropdown.hasClass('is-open');
            closeAll();
            setDropdownOpen($dropdown, shouldOpen);
        });

        $(document).on('click', function (e) {
            if ($(e.target).closest('[data-dropdown]').length) return;
            closeAll();
        });

        $(document).on('keydown', function (e) {
            if (e.key !== 'Escape') return;
            closeAll();
        });

        $(document).on('click', '[data-dropdown] .dropdown-item', function () {
            closeAll();
        });
    };

    initNavbarToggle();
    initDropdowns();

    const initBeginnerGuide = () => {
        const dismissedKey = 'beginner_guide_dismissed_v1';
        const $guide = $('#beginner-guide');
        const $open = $('#beginner-guide-open');
        const $close = $('#beginner-guide-close');

        if (!$guide.length) return;

        const isDismissed = localStorage.getItem(dismissedKey) === '1';
        $guide.toggle(!isDismissed);
        if ($open.length) $open.toggle(isDismissed);

        if ($close.length) {
            $close.on('click', function () {
                localStorage.setItem(dismissedKey, '1');
                $guide.hide();
                if ($open.length) $open.show();
            });
        }

        if ($open.length) {
            $open.on('click', function () {
                localStorage.removeItem(dismissedKey);
                $guide.show();
                $open.hide();
            });
        }

        $(document).on('keydown', function (e) {
            if (e.key !== 'Escape') return;
            if (!$guide.is(':visible')) return;
            localStorage.setItem(dismissedKey, '1');
            $guide.hide();
            if ($open.length) $open.show();
        });
    };

    initBeginnerGuide();

    const applyRamiPatternFromCssVar = () => {
        const elements = document.querySelectorAll('.rami-card, .rami-card-large, .print-rami-card');
        if (!elements.length) return;

        const patternInput = document.querySelector('[data-css-var="--rami-selected-pattern"]');
        let pattern = patternInput ? String($(patternInput).val() || '').trim() : '';

        if (!pattern) {
            pattern = String(window.getComputedStyle(elements[0]).getPropertyValue('--rami-selected-pattern') || '').trim();
        }

        if (!pattern) pattern = 'plain';
        elements.forEach((el) => {
            const currentPattern = String(el.getAttribute('data-pattern') || '').trim();
            if (!currentPattern || currentPattern === 'plain') {
                el.setAttribute('data-pattern', pattern);
            }
        });
    };

    applyRamiPatternFromCssVar();

    const applyRamiCardBackPatternFromCssVar = () => {
        const elements = document.querySelectorAll('.rami-card-back, .print-rami-card-back');
        if (!elements.length) return;

        const patternInput = document.querySelector('[data-css-var="--rami-card-back-pattern"]');
        let pattern = patternInput ? String($(patternInput).val() || '').trim() : '';

        if (!pattern) {
            const referenceElement = document.querySelector('.rami-card-back') || document.querySelector('.rami-card') || document.querySelector('.print-rami-card');
            if (referenceElement) {
                pattern = String(window.getComputedStyle(referenceElement).getPropertyValue('--rami-card-back-pattern') || '').trim();
            }
        }

        if (!pattern) pattern = 'diamonds';
        elements.forEach((el) => {
            const currentPattern = String(el.getAttribute('data-pattern') || '').trim();
            if (!currentPattern || currentPattern === 'plain') {
                el.setAttribute('data-pattern', pattern);
            }
        });
    };

    applyRamiCardBackPatternFromCssVar();

    const applyRamiIllustrationDesignFromCssVar = () => {
        const wrappers = document.querySelectorAll('.rami-card, .rami-card-large, .print-rami-card');
        if (!wrappers.length) return;

        const designInput = document.querySelector('[data-css-var="--rami-illustration-design"]');
        let design = designInput ? String($(designInput).val() || '').trim() : '';

        if (!design) {
            design = String(window.getComputedStyle(wrappers[0]).getPropertyValue('--rami-illustration-design') || '').trim();
        }

        if (!design) design = 'none';

        const targets = document.querySelectorAll('.rami-card-illustration, .rami-large-illustration-frame');
        if (!targets.length) return;

        targets.forEach((el) => {
            if (design === 'none') {
                el.removeAttribute('data-illustration-design');
                return;
            }
            el.setAttribute('data-illustration-design', design);
        });
    };

    applyRamiIllustrationDesignFromCssVar();

    const initPrintSuitRandomizer = () => {
        const button = document.getElementById('print-random-suits');
        if (!button) return;

        const suits = [
            { symbol: '♠', title: 'Pique (♠)' },
            { symbol: '♦', title: 'Carreaux (♦)' },
            { symbol: '♣', title: 'Trèfle (♣)' },
            { symbol: '♥', title: 'Cœur (♥)' },
        ];

        const applySuitToCard = (cardEl, suit) => {
            const suitEls = cardEl.querySelectorAll('.rami-card-suit');
            if (!suitEls.length) return;

            suitEls.forEach((el) => {
                el.textContent = suit.symbol;
                el.setAttribute('title', suit.title);
            });
        };

        const updateLegend = () => {
            const legends = Array.from(document.querySelectorAll('.print-legend'));
            if (!legends.length) return;

            legends.forEach((el) => {
                const rawText = String(el.textContent || '').trim();
                if (!rawText) return;
                if (!rawText.includes('♠') && !rawText.includes('♦') && !rawText.includes('♣') && !rawText.includes('♥')) return;

                const hasA4Prefix = rawText.startsWith('9 cartes par page A4');
                const giftMatch = rawText.match(/·\s*Icône\s*«\s*cadeau\s*»[\s\S]*$/);
                const giftHint = giftMatch ? ` ${giftMatch[0].trim()}` : '';

                el.textContent = `${hasA4Prefix ? '9 cartes par page A4 · ' : ''}Symboles : aléatoire (♠ ♦ ♣ ♥)${giftHint}`;
            });
        };

        const randomize = () => {
            document.querySelectorAll('.print-rami-card').forEach((cardEl) => {
                const suit = suits[Math.floor(Math.random() * suits.length)];
                applySuitToCard(cardEl, suit);
            });
            updateLegend();
        };

        button.addEventListener('click', (e) => {
            e.preventDefault();
            randomize();
        });
    };

    initPrintSuitRandomizer();

    $(document).on('click', '.rami-card[data-href]', function (e) {
        if ($(e.target).closest('button, a, input, select, textarea, label').length) return;
        const href = String($(this).data('href') || '').trim();
        if (href) window.location.href = href;
    });

    $(document).on('keydown', '.rami-card[data-href]', function (e) {
        const key = String(e.key || '');
        if (key !== 'Enter' && key !== ' ') return;
        if ($(e.target).closest('button, a, input, select, textarea, label').length) return;
        e.preventDefault();
        const href = String($(this).data('href') || '').trim();
        if (href) window.location.href = href;
    });

    const getCsrfToken = () => String($('meta[name="csrf-token"]').attr('content') || '').trim();

    $(document).on('click', '.rami-card-favorite[data-favorite-verb-id], .favorite-toggle[data-favorite-verb-id]', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const $btn = $(this);
        const verbId = String($btn.data('favorite-verb-id') || '').trim();
        const csrfToken = getCsrfToken();
        if (!verbId || !csrfToken) return;
        if ($btn.data('isLoading')) return;

        $btn.data('isLoading', true);
        $btn.prop('disabled', true).attr('aria-disabled', 'true');

        $.ajax({
            url: `/favorites/${encodeURIComponent(verbId)}/toggle`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken },
            success: function (data) {
                const favorited = !!(data && data.favorited);
                $btn.toggleClass('is-active', favorited);

                const $icon = $btn.find('i').first();
                if ($icon.length) {
                    $icon.addClass('ph-star');
                    $icon.toggleClass('ph-fill', favorited);
                }

                const label = favorited ? 'Retirer des favoris' : 'Ajouter aux favoris';
                $btn.attr('aria-label', label).attr('title', label);
                const $labelSpan = $btn.find('span').first();
                if ($labelSpan.length) $labelSpan.text(label);

                if (typeof window.showNotification === 'function') {
                    window.showNotification(favorited ? 'Ajouté aux favoris' : 'Retiré des favoris', 'success');
                }

                if (!favorited && window.location && String(window.location.pathname || '').startsWith('/favorites')) {
                    window.location.reload();
                }
            },
            error: function () {
                if (typeof window.showNotification === 'function') {
                    window.showNotification('Impossible de mettre à jour les favoris', 'error');
                }
            },
            complete: function () {
                $btn.data('isLoading', false);
                $btn.prop('disabled', false).removeAttr('aria-disabled');
            },
        });
    });

    const recentKey = 'recent_verbs_v1';
    const readRecent = () => {
        try {
            const raw = localStorage.getItem(recentKey);
            const parsed = raw ? JSON.parse(raw) : [];
            return Array.isArray(parsed) ? parsed : [];
        } catch {
            return [];
        }
    };
    const writeRecent = (items) => {
        try {
            localStorage.setItem(recentKey, JSON.stringify(items));
        } catch {
        }
    };
    const upsertRecent = (item) => {
        const id = Number(item.id);
        if (!Number.isFinite(id)) return;

        const url = String(item.url || '').trim();
        const infinitive = String(item.infinitive || '').trim();
        const group = String(item.group || '').trim();
        if (!url || !infinitive) return;

        const now = Date.now();
        const next = readRecent()
            .filter((x) => x && Number(x.id) !== id)
            .slice(0, 11);

        next.unshift({ id, url, infinitive, group, ts: now });
        writeRecent(next);
    };

    const $verbShowRoot = $('#verb-show-root');
    if ($verbShowRoot.length) {
        upsertRecent({
            id: $verbShowRoot.data('verb-id'),
            url: $verbShowRoot.data('verb-url'),
            infinitive: $verbShowRoot.data('verb-infinitive'),
            group: $verbShowRoot.data('verb-group'),
        });
    }

    const $recentContainer = $('#recently-viewed');
    const $recentList = $('#recently-viewed-list');
    if ($recentContainer.length && $recentList.length) {
        const renderRecent = () => {
            const items = readRecent();
            $recentList.empty();

            if (!items.length) {
                $recentContainer.hide();
                return;
            }

            items.slice(0, 8).forEach((item) => {
                const label = item.group ? `${item.infinitive} (${item.group})` : String(item.infinitive || '');
                const safeUrl = String(item.url || '').trim();
                if (!label || !safeUrl) return;
                $recentList.append(`<a class="btn btn-secondary" href="${safeUrl}">${label}</a>`);
            });

            $recentContainer.show();
        };

        renderRecent();

        $('#recently-viewed-clear').on('click', function () {
            writeRecent([]);
            renderRecent();
            if (typeof window.showNotification === 'function') {
                window.showNotification('Historique effacé', 'success');
            }
        });
    }

    const $flashToggle = $('#flashcard-toggle');
    const $flashStage = $('#flashcard-stage');
    const $flashFront = $('#flashcard-front');
    const $flashBack = $('#flashcard-back');
    const $flashRandom = $('#flashcard-random');

    if ($flashToggle.length && $flashStage.length && $flashFront.length && $flashBack.length) {
        const setSide = (side) => {
            const normalized = side === 'back' ? 'back' : 'front';
            $flashStage.attr('data-side', normalized);
        };

        const setMode = (enabled) => {
            const isEnabled = !!enabled;
            $flashStage.attr('data-enabled', isEnabled ? '1' : '0');
            const label = isEnabled ? 'Quitter le mode flashcard' : 'Mode flashcard';
            $flashToggle.find('span').first().text(label);
            $flashToggle.toggleClass('is-active', isEnabled);
            $flashRandom.toggle(isEnabled);

            if (!isEnabled) {
                $flashStage.removeAttr('data-side');
                return;
            }

            setSide('front');
        };

        setMode(false);

        $flashToggle.on('click', function () {
            const enabled = String($flashStage.attr('data-enabled') || '') !== '1';
            setMode(enabled);
        });

        const flip = () => {
            if (String($flashStage.attr('data-enabled') || '') !== '1') return;
            const current = String($flashStage.attr('data-side') || 'front');
            setSide(current === 'front' ? 'back' : 'front');
        };

        $flashStage.on('click', function (e) {
            if ($(e.target).closest('button, a, input, select, textarea, label').length) return;
            flip();
        });

        $flashStage.on('keydown', function (e) {
            if (e.key !== 'Enter' && e.key !== ' ') return;
            if ($(e.target).closest('button, a, input, select, textarea, label').length) return;
            e.preventDefault();
            flip();
        });

        $flashRandom.on('click', function () {
            const group = String($flashStage.data('verb-group') || '').trim();
            const currentId = Number($flashStage.data('verb-id'));
            const query = group ? `?group=${encodeURIComponent(group)}` : '';

            $.ajax({
                url: `/api/verbs${query}`,
                method: 'GET',
                success: function (verbs) {
                    if (!Array.isArray(verbs) || verbs.length < 2) return;
                    const candidates = verbs.filter((v) => v && Number(v.id) !== currentId);
                    if (!candidates.length) return;
                    const pick = candidates[Math.floor(Math.random() * candidates.length)];
                    if (pick && pick.id) {
                        const path = String(window.location.pathname || '');
                        const isV3 = /\/cards\/[^/]+\/v3$/.test(path);
                        window.location.href = isV3 ? `/cards/${pick.id}/v3` : `/cards/${pick.id}`;
                    }
                },
            });
        });
    }

    const $quizRoot = $('#quiz-root');
    if ($quizRoot.length) {
        const $start = $('#quiz-start');
        const $mode = $('#quiz-mode');
        const $group = $('#quiz-group');
        const $totalInput = $('#quiz-total');
        const $timerInput = $('#quiz-timer');
        const $timerWrap = $('#quiz-timer-wrap');
        const $timerLeft = $('#quiz-timer-left');
        const $revealAllToggle = $('#quiz-reveal-all');
        const $best = $('#quiz-best');
        const $questions = $('#quiz-questions');
        const $score = $('#quiz-score');
        const $prompt = $('#quiz-prompt');
        const $answer = $('#quiz-answer');
        const $submit = $('#quiz-submit');
        const $reveal = $('#quiz-reveal');
        const $revealAllWrap = $('#quiz-reveal-all-wrap');
        const $next = $('#quiz-next');
        const $feedback = $('#quiz-feedback');

        let pool = [];
        let current = null;
        let score = 0;
        let asked = 0;
        let total = 10;
        let timerSeconds = 60;
        let timerLeft = 60;
        let timerId = null;

        const quizBestKey = 'frenchverbs_quiz_best_v1';
        const quizProgressKey = 'frenchverbs_quiz_progress_v1';

        const normalize = (s) => String(s || '').toLowerCase().replace(/\s+/g, ' ').trim();
        const pickRandom = (arr) => arr[Math.floor(Math.random() * arr.length)];

        const escapeHtml = (value) => {
            return String(value ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        };

        const getBestKey = () => {
            const group = String($group.val() || '').trim() || 'all';
            const mode = String($mode.val() || 'standard').trim() || 'standard';
            return `${mode}:${group}`;
        };

        const loadBest = () => {
            try {
                const raw = localStorage.getItem(quizBestKey);
                const parsed = raw ? JSON.parse(raw) : null;
                if (!parsed || typeof parsed !== 'object') return null;
                return parsed;
            } catch {
                return null;
            }
        };

        const renderBest = () => {
            const all = loadBest();
            const key = getBestKey();
            const best = all && typeof all[key] === 'object' ? all[key] : null;
            if (!best) {
                $best.text('—');
                return;
            }
            const label = best.mode === 'timed'
                ? `${Number(best.score) || 0} pts`
                : `${Number(best.score) || 0} / ${Number(best.total) || 0}`;
            $best.text(label);
        };

        const saveBestIfNeeded = () => {
            const mode = String($mode.val() || 'standard');
            const key = getBestKey();
            const payload = {
                mode,
                score,
                total,
                updatedAt: Date.now(),
            };

            try {
                const all = loadBest() || {};
                const prev = all && typeof all[key] === 'object' ? all[key] : null;
                let shouldWrite = false;

                if (!prev) {
                    shouldWrite = true;
                } else if (mode === 'timed') {
                    shouldWrite = (Number(payload.score) || 0) > (Number(prev.score) || 0);
                } else {
                    const prevRate = (Number(prev.total) || 0) > 0 ? (Number(prev.score) || 0) / (Number(prev.total) || 1) : 0;
                    const nextRate = (Number(payload.total) || 0) > 0 ? (Number(payload.score) || 0) / (Number(payload.total) || 1) : 0;
                    shouldWrite = nextRate > prevRate;
                }

                if (!shouldWrite) return;
                all[key] = payload;
                localStorage.setItem(quizBestKey, JSON.stringify(all));
            } catch {
            }
            renderBest();
        };

        const loadProgress = () => {
            try {
                const raw = localStorage.getItem(quizProgressKey);
                const parsed = raw ? JSON.parse(raw) : null;
                return parsed && typeof parsed === 'object' ? parsed : {};
            } catch {
                return {};
            }
        };

        const saveProgress = (progress) => {
            try {
                localStorage.setItem(quizProgressKey, JSON.stringify(progress));
            } catch {
            }
        };

        const recordProgress = ({ verbId, isCorrect } = {}) => {
            const id = Number(verbId);
            if (!id) return;
            const p = loadProgress();
            const row = p[id] && typeof p[id] === 'object' ? p[id] : {};
            const correct = Number(row.correct) || 0;
            const wrong = Number(row.wrong) || 0;
            p[id] = {
                correct: isCorrect ? correct + 1 : correct,
                wrong: isCorrect ? wrong : wrong + 1,
                lastAt: Date.now(),
            };
            saveProgress(p);

            const csrfToken = getCsrfToken();
            if (!csrfToken) return;
            $.ajax({
                url: '/progress/answer',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                data: { verb_id: id, is_correct: isCorrect ? 1 : 0 },
            });
        };

        const mulberry32 = (seed) => {
            let a = seed >>> 0;
            return function () {
                a |= 0;
                a = a + 0x6D2B79F5 | 0;
                let t = Math.imul(a ^ a >>> 15, 1 | a);
                t = t + Math.imul(t ^ t >>> 7, 61 | t) ^ t;
                return ((t ^ t >>> 14) >>> 0) / 4294967296;
            };
        };

        const stableShuffle = (arr, seed) => {
            const copy = Array.isArray(arr) ? arr.slice() : [];
            const rand = mulberry32(seed);
            for (let i = copy.length - 1; i > 0; i--) {
                const j = Math.floor(rand() * (i + 1));
                const tmp = copy[i];
                copy[i] = copy[j];
                copy[j] = tmp;
            }
            return copy;
        };

        const buildDailySeed = () => {
            const group = String($group.val() || '').trim() || 'all';
            const d = new Date();
            const stamp = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
            let hash = 0;
            const raw = `daily:${group}:${stamp}`;
            for (let i = 0; i < raw.length; i++) {
                hash = (hash * 31 + raw.charCodeAt(i)) >>> 0;
            }
            return hash;
        };

        const pickQuestion = () => {
            const verb = pickRandom(pool);
            const present = Array.isArray(verb.present) ? verb.present : [];
            if (!present.length) return null;
            const line = pickRandom(present);
            if (!line || !line.pronoun || !line.conjugation) return null;
            return { verb, pronoun: line.pronoun, expected: line.conjugation };
        };

        const render = () => {
            if (!current) return;
            const translation = current.verb.translation ? ` (${current.verb.translation})` : '';
            const promptText = `${current.verb.infinitive}${translation} · ${current.pronoun}`;
            $prompt.html(`
                <span>${escapeHtml(promptText)}</span>
                <button type="button" class="btn btn-secondary btn-icon-only btn-audio-player" data-text="${escapeHtml(current.verb.infinitive)}" title="Prononcer">
                    <i class="ph ph-speaker-high"></i>
                </button>
            `);
            $answer.val('').trigger('focus');
            $feedback.text('');
            $reveal.hide();
            $revealAllWrap.hide().empty();
            $next.hide();
            $submit.show();
            $score.text(`${score} / ${asked}`);
            $questions.text(`${asked + 1} / ${total}`);
        };

        const endQuiz = () => {
            $submit.hide();
            $next.hide();
            $reveal.hide();
            $revealAllWrap.hide().empty();
            $feedback.text(`Terminé. Score: ${score} / ${total}`);
            if (timerId) {
                window.clearInterval(timerId);
                timerId = null;
            }
            $timerWrap.hide();
            saveBestIfNeeded();
        };

        const nextQuestion = () => {
            if (asked >= total) {
                endQuiz();
                return;
            }

            let q = null;
            for (let i = 0; i < 20; i++) {
                q = pickQuestion();
                if (q) break;
            }
            current = q;
            if (!current) {
                if (typeof window.showNotification === 'function') {
                    window.showNotification('Pas assez de données pour démarrer le quiz', 'error');
                }
                endQuiz();
                return;
            }
            render();
        };

        const loadPool = () => {
            const group = String($group.val() || '').trim();
            const query = group ? `?group=${encodeURIComponent(group)}` : '';
            return $.ajax({ url: `/api/verbs${query}`, method: 'GET' });
        };

        const startQuiz = async () => {
            if (timerId) {
                window.clearInterval(timerId);
                timerId = null;
            }

            const mode = String($mode.val() || 'standard').trim() || 'standard';
            const totalVal = Number($totalInput.val());
            const timerVal = Number($timerInput.val());
            total = Number.isFinite(totalVal) ? Math.max(5, Math.min(50, totalVal)) : 10;
            timerSeconds = Number.isFinite(timerVal) ? Math.max(15, Math.min(300, timerVal)) : 60;

            score = 0;
            asked = 0;
            pool = [];
            current = null;

            $feedback.text('');
            $reveal.hide();
            $revealAllWrap.hide().empty();
            $next.hide();
            $submit.hide();
            $score.text('0 / 0');
            $questions.text(`1 / ${total}`);

            try {
                const verbs = await loadPool();
                pool = Array.isArray(verbs) ? verbs.filter((v) => v && Array.isArray(v.present) && v.present.length) : [];
            } catch {
                pool = [];
            }

            if (pool.length < 3) {
                if (typeof window.showNotification === 'function') {
                    window.showNotification('Aucun verbe disponible pour ce filtre', 'error');
                }
                endQuiz();
                return;
            }

            if (mode === 'daily') {
                const seed = buildDailySeed();
                pool = stableShuffle(pool, seed).slice(0, Math.min(pool.length, Math.max(20, total)));
                total = Math.min(total, pool.length);
                $questions.text(`1 / ${total}`);
            }

            if (mode === 'review') {
                const progress = loadProgress();
                const ranked = pool
                    .map((v) => {
                        const id = Number(v && v.id);
                        const p = id && progress[id] && typeof progress[id] === 'object' ? progress[id] : {};
                        const correct = Number(p.correct) || 0;
                        const wrong = Number(p.wrong) || 0;
                        const score = (wrong * 2) - correct;
                        return { v, score };
                    })
                    .sort((a, b) => (b.score - a.score));
                const slice = ranked.filter((x) => x.score > 0).slice(0, Math.min(60, ranked.length)).map((x) => x.v);
                if (slice.length >= 5) {
                    pool = slice;
                }
            }

            if (mode === 'timed') {
                timerLeft = timerSeconds;
                $timerWrap.show();
                $timerLeft.text(`${timerLeft}s`);
                timerId = window.setInterval(() => {
                    timerLeft -= 1;
                    $timerLeft.text(`${Math.max(0, timerLeft)}s`);
                    if (timerLeft <= 0) {
                        window.clearInterval(timerId);
                        timerId = null;
                        endQuiz();
                    }
                }, 1000);
            } else {
                $timerWrap.hide();
            }

            nextQuestion();
        };

        $start.on('click', function () {
            startQuiz();
        });

        const renderRevealAll = () => {
            if (!current) return;
            if (!$revealAllToggle.is(':checked')) return;
            const lines = Array.isArray(current.verb.present) ? current.verb.present : [];
            if (!lines.length) return;

            const html = `
                <div class="form-label">Formes</div>
                <div class="card-conjugations-preview" style="margin-top: 10px;">
                    ${lines.map((l) => {
                const p = escapeHtml(l && l.pronoun ? l.pronoun : '');
                const v = escapeHtml(l && l.conjugation ? l.conjugation : '');
                return `
                            <div class="conjugation-row" style="align-items: center;">
                                <span class="conjugation-pronoun">${p}</span>
                                <span class="conjugation-form">${v}</span>
                                <button type="button" class="btn btn-secondary btn-icon-only btn-audio-player" data-text="${v}" title="Prononcer">
                                    <i class="ph ph-speaker-high"></i>
                                </button>
                            </div>
                        `;
            }).join('')}
                </div>
            `;
            $revealAllWrap.html(html).show();
        };

        const reveal = (isCorrect) => {
            const expected = current ? String(current.expected || '') : '';
            const expectedSafe = escapeHtml(expected);
            $reveal.html(`
                <span>Réponse: ${expectedSafe}</span>
                <button type="button" class="btn btn-secondary btn-icon-only btn-audio-player" data-text="${expectedSafe}" title="Prononcer">
                    <i class="ph ph-speaker-high"></i>
                </button>
            `).show();
            $feedback.text(isCorrect ? 'Correct' : 'Incorrect');
            $submit.hide();
            $next.show();
            renderRevealAll();
        };

        $submit.on('click', function () {
            if (!current) return;
            const userAnswer = normalize($answer.val());
            const expected = normalize(current.expected);
            asked += 1;
            if (userAnswer && userAnswer === expected) {
                score += 1;
                recordProgress({ verbId: current.verb.id, isCorrect: true });
                if (typeof window.showNotification === 'function') {
                    window.showNotification('Bonne réponse', 'success');
                }
                reveal(true);
                return;
            }
            recordProgress({ verbId: current.verb.id, isCorrect: false });
            if (typeof window.showNotification === 'function') {
                window.showNotification('Mauvaise réponse', 'error');
            }
            reveal(false);
        });

        $answer.on('keydown', function (e) {
            if (e.key !== 'Enter') return;
            e.preventDefault();
            $submit.trigger('click');
        });

        $next.on('click', function () {
            nextQuestion();
        });

        const syncModeInputs = () => {
            const mode = String($mode.val() || 'standard');
            $timerInput.prop('disabled', mode !== 'timed');
            $timerWrap.toggle(mode === 'timed' && timerId);
            if (mode === 'daily') {
                $totalInput.val('25');
            }
            renderBest();
        };

        $mode.on('change', syncModeInputs);
        $group.on('change', renderBest);

        syncModeInputs();
        renderBest();
        startQuiz();
    }

    // ========================================
    // CARD HOVER EFFECTS
    // ========================================
    $('.verb-card').on('mouseenter', function () {
        $(this).css('cursor', 'pointer');
    });

    // ========================================
    // SMOOTH SCROLL
    // ========================================
    $('a[href^="#"]').on('click', function (e) {
        e.preventDefault();
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 80
            }, 500);
        }
    });

    // ========================================
    // FORM ENHANCEMENTS
    // ========================================
    // Focus effect on inputs
    $('.form-input').on('focus', function () {
        $(this).parent().addClass('focused');
    }).on('blur', function () {
        $(this).parent().removeClass('focused');
    });

    const $infinitiveInput = $('#infinitive');
    const $groupSelect = $('#group');

    // Check for either the old select or new radio inputs
    const $groupInputs = $('input[name="group"]');
    const $suitInputs = $('input[name="suit"]');
    const $suitDisplayInputs = $('input[name="suit_display"]');
    const $suitHiddenInput = $('input[type="hidden"][name="suit"]');

    if ($infinitiveInput.length && ($groupSelect.length || $groupInputs.length)) {
        const getGroupValue = () => {
            if ($groupSelect.length) return $groupSelect.val();
            return $('input[name="group"]:checked').val();
        };

        const getSuitValue = () => {
            if ($suitHiddenInput.length) return $suitHiddenInput.val();
            if (!$suitInputs.length) return null;
            const $radio = $suitInputs.filter(':radio');
            if ($radio.length) return $('input[name="suit"]:checked').val();
            if ($suitInputs.length === 1) return $suitInputs.val();
            return null;
        };

        const setSuitValue = (value) => {
            const suit = String(value || '').trim() || 'spade';

            if ($suitHiddenInput.length) {
                $suitHiddenInput.val(suit).trigger('change');
            }

            if ($suitDisplayInputs.length) {
                $suitDisplayInputs.prop('checked', false);
                $suitDisplayInputs.filter(`[value="${suit}"]`).prop('checked', true);
            } else if ($suitInputs.filter(':radio').length) {
                $suitInputs.prop('checked', false);
                $suitInputs.filter(`[value="${suit}"]`).prop('checked', true).trigger('change');
            }
        };

        const fillIfEmpty = (selector, value) => {
            const $field = $(selector);
            if ($field.length && (!$field.val() || $field.val() === '...')) {
                $field.val(value);
            }
        };

        const setFieldValue = (selector, value) => {
            const $field = $(selector);
            if ($field.length) {
                $field.val(value).trigger('input');
            }
        };

        const updateThemeColorForGroup = () => {
            const group = getGroupValue();
            const colors = { '1er': '#1e3a5f', '2ème': '#2d5a3d', '3ème': '#5a2d5a' };
            const $themeColor = $('#theme_color');

            // Only update if user hasn't manually changed it significantly? 
            // For now, simple logic: if it matches one of the defaults, switch it.
            // Or just always switch it for better UX.
            if ($themeColor.length) {
                const newColor = colors[group] || '#1e3a5f';
                $themeColor.val(newColor).trigger('input');
            }

            const accentColors = { '1er': '#5b9bd5', '2ème': '#4fb286', '3ème': '#c084fc' };
            const $accentColor = $('#accent_color');
            if ($accentColor.length) {
                const newAccent = accentColors[group] || '#5b9bd5';
                $accentColor.val(newAccent).trigger('input');
            }
        };

        const normalizeInfinitive = () => {
            const raw = String($infinitiveInput.val() || '');
            const normalized = raw.trim().toLowerCase();
            if (raw !== normalized) {
                $infinitiveInput.val(normalized);
            }
            return normalized;
        };

        const extractPronominalInfinitive = (infinitive) => {
            const raw = String(infinitive || '').trim().toLowerCase();
            const match = raw.match(/^(se\s+|s['’])(.+)$/i);
            if (match && match[2]) {
                return { isPronominal: true, base: String(match[2]).trim() };
            }
            return { isPronominal: false, base: raw };
        };

        const startsWithVowelOrH = (word) => {
            const s = String(word || '').trim().toLowerCase();
            if (!s) return false;
            const first = s.charAt(0);
            const vowels = ['a', 'à', 'â', 'ä', 'e', 'é', 'è', 'ê', 'ë', 'i', 'î', 'ï', 'o', 'ô', 'ö', 'u', 'ù', 'û', 'ü', 'y', 'œ'];
            if (vowels.includes(first)) return true;
            return first === 'h';
        };

        const withReflexivePronouns = (forms) => {
            if (!forms) return null;

            const apply = (value, pronoun) => {
                const v = String(value || '').trim();
                if (!v) return v;
                if (pronoun === 'nous' || pronoun === 'vous') return `${pronoun} ${v}`;
                if (!startsWithVowelOrH(v)) return `${pronoun} ${v}`;
                return `${pronoun.charAt(0)}’${v}`;
            };

            return {
                je: apply(forms.je, 'me'),
                tu: apply(forms.tu, 'te'),
                'il/elle/on': apply(forms['il/elle/on'], 'se'),
                nous: apply(forms.nous, 'nous'),
                vous: apply(forms.vous, 'vous'),
                'ils/elles': apply(forms['ils/elles'], 'se'),
            };
        };

        const syncSuitToGroup = () => {
            const infinitive = String($infinitiveInput.val() || '').trim().toLowerCase();
            const group = String(getGroupValue() || '').trim();

            if (infinitive === 'avoir' || infinitive === 'être' || infinitive === 'etre') {
                setSuitValue('spade');
                return;
            }

            if (infinitive === 'aller') {
                if (group !== '3ème') {
                    if ($groupSelect.length) {
                        $groupSelect.val('3ème');
                    } else if ($groupInputs.length) {
                        $groupInputs.prop('checked', false);
                        $groupInputs.filter('[value="3ème"]').prop('checked', true);
                    }
                    updateThemeColorForGroup();
                }
                setSuitValue('diamond');
                return;
            }

            const suitByGroup = { '1er': 'heart', '2ème': 'club', '3ème': 'diamond' };
            setSuitValue(suitByGroup[group] || 'spade');
        };

        const normalizeField = (selector) => {
            const $field = $(selector);
            if (!$field.length) return;
            const raw = String($field.val() || '');
            const normalized = raw.trim();
            if (raw !== normalized) {
                $field.val(normalized);
            }
        };

        const suggestConjugations = () => {
            const infinitive = normalizeInfinitive();
            const extracted = extractPronominalInfinitive(infinitive);
            const baseInfinitive = extracted.base;
            const group = getGroupValue();

            let conjugations = null;

            if (group === '1er' && baseInfinitive.endsWith('er')) {
                conjugations = window.FrenchVerbs.conjugate1erGroupe(baseInfinitive);
            } else if (group === '2ème' && baseInfinitive.endsWith('ir')) {
                conjugations = window.FrenchVerbs.conjugate2emeGroupe(baseInfinitive);
            }

            if (conjugations && extracted.isPronominal) {
                conjugations = withReflexivePronouns(conjugations);
            }

            if (conjugations) {
                fillIfEmpty('#je', conjugations.je);
                fillIfEmpty('#tu', conjugations.tu);
                fillIfEmpty('#il_elle_on', conjugations['il/elle/on']);
                fillIfEmpty('#nous', conjugations.nous);
                fillIfEmpty('#vous', conjugations.vous);
                fillIfEmpty('#ils_elles', conjugations['ils/elles']);
            }
        };

        // Magic Conjugation Button Logic
        $('#magic-conjugate').on('click', function () {
            const btn = $(this);
            const icon = btn.find('i');

            // Animate button
            icon.addClass('ph-spinner').removeClass('ph-sparkle');
            btn.prop('disabled', true);

            const infinitive = normalizeInfinitive();
            const extracted = extractPronominalInfinitive(infinitive);
            const baseInfinitive = extracted.base;
            let group = getGroupValue();

            // Auto-detect group if possible
            if (baseInfinitive === 'aller' && group !== '3ème') {
                $('input[name="group"][value="3ème"]').prop('checked', true).trigger('change');
                group = '3ème';
            } else if (baseInfinitive.endsWith('er') && group !== '1er') {
                $('input[name="group"][value="1er"]').prop('checked', true).trigger('change');
                group = '1er';
            } else if (baseInfinitive.endsWith('ir') && !baseInfinitive.endsWith('oir') && group !== '2ème') {
                // Simplistic detection for 2nd group, not perfect but helpful
                $('input[name="group"][value="2ème"]').prop('checked', true).trigger('change');
                group = '2ème';
            }

            setTimeout(() => {
                // Force fill (overwrite)
                let conjugations = null;
                if (group === '1er' && baseInfinitive.endsWith('er')) {
                    conjugations = window.FrenchVerbs.conjugate1erGroupe(baseInfinitive);
                } else if (group === '2ème' && baseInfinitive.endsWith('ir')) {
                    conjugations = window.FrenchVerbs.conjugate2emeGroupe(baseInfinitive);
                }

                if (conjugations && extracted.isPronominal) {
                    conjugations = withReflexivePronouns(conjugations);
                }

                if (conjugations) {
                    setFieldValue('#je', conjugations.je);
                    setFieldValue('#tu', conjugations.tu);
                    setFieldValue('#il_elle_on', conjugations['il/elle/on']);
                    setFieldValue('#nous', conjugations.nous);
                    setFieldValue('#vous', conjugations.vous);
                    setFieldValue('#ils_elles', conjugations['ils/elles']);

                    showNotification('Conjugaison générée avec succès !', 'success');
                } else {
                    showNotification('Impossible de conjuguer ce verbe automatiquement.', 'error');
                }

                icon.removeClass('ph-spinner').addClass('ph-sparkle');
                btn.prop('disabled', false);
            }, 600); // Fake delay for UX
        });

        // Update Color Display Text
        $('.form-input-color').on('input change', function () {
            $(this).siblings('.color-value').text($(this).val());
            updateCreatePreview();
        });

        const $createPreviewCard = $('#create-preview-card');
        const $createPreviewGroup = $('#create-preview-group');
        const $createPreviewInfinitive = $('#create-preview-infinitive');
        const $createPreviewTranslation = $('#create-preview-translation');
        const $createPreviewSuit = $('#create-preview-suit');
        const $createPreviewSuitBadge = $('#create-preview-suit-badge');
        const $createPreviewSuitBottom = $('#create-preview-suit-bottom');
        const $createPreviewJeIndexTop = $('#create-preview-je-index-top');
        const $createPreviewJeIndexBottom = $('#create-preview-je-index-bottom');

        const hexToRgba = (hex, alpha) => {
            const h = String(hex || '').trim().replace('#', '');
            if (!/^[0-9a-fA-F]{6}$/.test(h)) return '';
            const r = parseInt(h.slice(0, 2), 16);
            const g = parseInt(h.slice(2, 4), 16);
            const b = parseInt(h.slice(4, 6), 16);
            return `rgba(${r}, ${g}, ${b}, ${alpha})`;
        };

        const updateCreatePreview = () => {
            if (!$createPreviewCard.length) return;

            const infinitive = String($infinitiveInput.val() || '').trim();
            const translation = String($('#infinitive_translation').val() || '').trim();
            const group = getGroupValue() || '1er';
            const suitValue = String(getSuitValue() || 'spade');
            const themeColor = String($('#theme_color').val() || '').trim() || '#1e3a5f';
            const accentColor = String($('#accent_color').val() || '').trim() || '#5b9bd5';

            const suitByValue = { spade: '♠', diamond: '♦', club: '♣', heart: '♥' };
            const suit = suitByValue[suitValue] || '♠';

            const patternColor = hexToRgba(accentColor, 0.04) || 'rgba(30, 58, 95, 0.03)';

            $createPreviewCard.attr('data-group', group);
            $createPreviewCard.attr('data-suit', suitValue);

            const pattern = String($('#pattern').val() || 'plain');
            $createPreviewCard.attr('data-pattern', pattern);

            $createPreviewCard.css({
                '--rami-accent-color': accentColor,
                '--rami-card-border-color': themeColor,
                '--rami-pattern-color': patternColor,
            });

            applyRamiPatternFromCssVar();

            if ($createPreviewGroup.length) {
                $createPreviewGroup.find('span').last().text(`${group} groupe`);
            }

            if ($createPreviewInfinitive.length) {
                $createPreviewInfinitive.text((infinitive || '...').toUpperCase());
            }

            if ($createPreviewTranslation.length) {
                $createPreviewTranslation.text(translation);
                $createPreviewTranslation.toggle(translation !== '');
            }

            const jeVal = String($('#je').val() || '').trim() || '...';
            $('#create-preview-je').text(jeVal);
            $('#create-preview-tu').text(String($('#tu').val() || '').trim() || '...');
            $('#create-preview-il').text(String($('#il_elle_on').val() || '').trim() || '...');
            $('#create-preview-nous').text(String($('#nous').val() || '').trim() || '...');
            $('#create-preview-vous').text(String($('#vous').val() || '').trim() || '...');
            $('#create-preview-ils').text(String($('#ils_elles').val() || '').trim() || '...');

            if ($createPreviewJeIndexTop.length) $createPreviewJeIndexTop.text(jeVal);
            if ($createPreviewJeIndexBottom.length) $createPreviewJeIndexBottom.text(jeVal);

            if ($createPreviewSuit.length) $createPreviewSuit.text(suit);
            if ($createPreviewSuitBadge.length) $createPreviewSuitBadge.text(suit);
            if ($createPreviewSuitBottom.length) $createPreviewSuitBottom.text(suit);
        };

        const isBuilderPage = $createPreviewCard.length > 0;
        const builderDraftKey = 'frenchverbs_builder_draft_v1';
        const builderHistoryKey = 'frenchverbs_builder_history_v1';
        const builderTutorialKey = 'frenchverbs_builder_tutorial_seen_v1';

        const setGroupValue = (value) => {
            const group = String(value || '').trim();
            if ($groupSelect.length) {
                $groupSelect.val(group).trigger('change');
                return;
            }
            const $target = $groupInputs.filter(`[value="${group}"]`);
            if ($target.length) {
                $target.prop('checked', true).trigger('change');
            }
        };

        const collectBuilderState = () => {
            return {
                infinitive: String($infinitiveInput.val() || '').trim(),
                infinitive_translation: String($('#infinitive_translation').val() || '').trim(),
                example_sentence: String($('#example_sentence').val() || '').trim(),
                group: String(getGroupValue() || '').trim(),
                suit: String(getSuitValue() || '').trim(),
                theme_color: String($('#theme_color').val() || '').trim(),
                accent_color: String($('#accent_color').val() || '').trim(),
                pattern: String($('#pattern').val() || '').trim(),
                je: String($('#je').val() || '').trim(),
                tu: String($('#tu').val() || '').trim(),
                il_elle_on: String($('#il_elle_on').val() || '').trim(),
                nous: String($('#nous').val() || '').trim(),
                vous: String($('#vous').val() || '').trim(),
                ils_elles: String($('#ils_elles').val() || '').trim(),
            };
        };

        const setBuilderState = (state) => {
            const s = state && typeof state === 'object' ? state : {};
            if (typeof s.group === 'string' && s.group.trim() !== '') {
                setGroupValue(s.group);
            }

            if (typeof s.infinitive === 'string') setFieldValue('#infinitive', s.infinitive);
            if (typeof s.infinitive_translation === 'string') setFieldValue('#infinitive_translation', s.infinitive_translation);
            if (typeof s.example_sentence === 'string') setFieldValue('#example_sentence', s.example_sentence);
            if (typeof s.theme_color === 'string' && s.theme_color.trim() !== '') $('#theme_color').val(s.theme_color).trigger('input');
            if (typeof s.accent_color === 'string' && s.accent_color.trim() !== '') $('#accent_color').val(s.accent_color).trigger('input');
            if (typeof s.pattern === 'string') $('#pattern').val(s.pattern).trigger('change');

            if (typeof s.je === 'string') setFieldValue('#je', s.je);
            if (typeof s.tu === 'string') setFieldValue('#tu', s.tu);
            if (typeof s.il_elle_on === 'string') setFieldValue('#il_elle_on', s.il_elle_on);
            if (typeof s.nous === 'string') setFieldValue('#nous', s.nous);
            if (typeof s.vous === 'string') setFieldValue('#vous', s.vous);
            if (typeof s.ils_elles === 'string') setFieldValue('#ils_elles', s.ils_elles);

            if (typeof s.suit === 'string' && s.suit.trim() !== '') {
                setSuitValue(s.suit);
            }

            updateCreatePreview();
        };

        const builderStateEquals = (a, b) => {
            try {
                return JSON.stringify(a) === JSON.stringify(b);
            } catch {
                return false;
            }
        };

        let builderIsApplying = false;
        let builderUndo = [];
        let builderRedo = [];

        const updateBuilderHistoryButtons = () => {
            const $undo = $('#builder-undo');
            const $redo = $('#builder-redo');
            if ($undo.length) $undo.prop('disabled', builderUndo.length === 0);
            if ($redo.length) $redo.prop('disabled', builderRedo.length === 0);
        };

        const pushBuilderHistory = (state) => {
            if (!isBuilderPage || builderIsApplying) return;
            const current = collectBuilderState();
            const snapshot = state && typeof state === 'object' ? state : current;
            const last = builderUndo.length ? builderUndo[builderUndo.length - 1] : null;
            if (last && builderStateEquals(last, snapshot)) {
                return;
            }
            builderUndo.push(snapshot);
            if (builderUndo.length > 30) {
                builderUndo = builderUndo.slice(builderUndo.length - 30);
            }
            builderRedo = [];
            updateBuilderHistoryButtons();
            try {
                localStorage.setItem(builderHistoryKey, JSON.stringify({
                    undo: builderUndo,
                    redo: builderRedo,
                }));
            } catch {
            }
        };

        const applyBuilderHistorySnapshot = (snapshot) => {
            builderIsApplying = true;
            try {
                setBuilderState(snapshot);
            } finally {
                builderIsApplying = false;
            }
        };

        const loadBuilderHistory = () => {
            try {
                const raw = localStorage.getItem(builderHistoryKey);
                if (!raw) return;
                const parsed = JSON.parse(raw);
                if (parsed && typeof parsed === 'object') {
                    builderUndo = Array.isArray(parsed.undo) ? parsed.undo : [];
                    builderRedo = Array.isArray(parsed.redo) ? parsed.redo : [];
                }
            } catch {
            }
            updateBuilderHistoryButtons();
        };

        const saveBuilderDraft = () => {
            if (!isBuilderPage || builderIsApplying) return;
            const payload = {
                updatedAt: Date.now(),
                state: collectBuilderState(),
            };
            try {
                localStorage.setItem(builderDraftKey, JSON.stringify(payload));
            } catch {
            }
        };

        const loadBuilderDraft = () => {
            try {
                const raw = localStorage.getItem(builderDraftKey);
                if (!raw) return null;
                const parsed = JSON.parse(raw);
                if (!parsed || typeof parsed !== 'object') return null;
                if (!parsed.state || typeof parsed.state !== 'object') return null;
                return parsed;
            } catch {
                return null;
            }
        };

        let builderSaveTimer = null;
        const queueBuilderSave = () => {
            if (!isBuilderPage) return;
            if (builderSaveTimer) window.clearTimeout(builderSaveTimer);
            builderSaveTimer = window.setTimeout(() => {
                const state = collectBuilderState();
                pushBuilderHistory(state);
                saveBuilderDraft();
            }, 350);
        };

        const builderTemplates = {
            classic: { theme_color: '#1e3a5f', accent_color: '#5b9bd5', pattern: 'plain' },
            ocean: { theme_color: '#0f52ba', accent_color: '#00d4ff', pattern: 'waves' },
            forest: { theme_color: '#1a5f3f', accent_color: '#7cb342', pattern: 'foliage' },
            lavender: { theme_color: '#5a2d5a', accent_color: '#c084fc', pattern: 'mandala' },
            contrast: { theme_color: '#111827', accent_color: '#ff6b6b', pattern: 'diamonds' },
        };

        const applyBuilderTemplate = (id) => {
            const tpl = builderTemplates[String(id || '')];
            if (!tpl) return;
            builderIsApplying = true;
            try {
                if (tpl.theme_color) $('#theme_color').val(tpl.theme_color).trigger('input');
                if (tpl.accent_color) $('#accent_color').val(tpl.accent_color).trigger('input');
                if (typeof tpl.pattern === 'string') $('#pattern').val(tpl.pattern).trigger('change');
                updateCreatePreview();
            } finally {
                builderIsApplying = false;
            }
            queueBuilderSave();
        };

        const ensureTutorialStyles = () => {
            if (document.getElementById('builder-tutorial-styles')) return;
            const style = document.createElement('style');
            style.id = 'builder-tutorial-styles';
            style.textContent = `
                .builder-tutorial-overlay{position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9999;display:flex;align-items:flex-end;justify-content:center;padding:24px}
                .builder-tutorial-card{width:min(720px,100%);background:var(--color-bg-card);border:1px solid var(--color-border);border-radius:16px;box-shadow:var(--shadow-xl);padding:18px 18px 14px}
                .builder-tutorial-title{font-weight:800;font-family:var(--font-display);margin-bottom:6px}
                .builder-tutorial-body{color:var(--color-text-secondary);line-height:1.5}
                .builder-tutorial-actions{margin-top:14px;display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap}
                .builder-tutorial-highlight{outline:3px solid rgba(91,155,213,.85);outline-offset:4px;border-radius:12px}
            `;
            document.head.appendChild(style);
        };

        const runBuilderTutorial = () => {
            if (!isBuilderPage) return;
            ensureTutorialStyles();

            const steps = [
                { title: 'Infinitif', body: 'Saisis un verbe, puis utilise Conjugaison Magique si besoin.', selector: '#infinitive' },
                { title: 'Groupe', body: 'Choisis le groupe : la couleur de la carte suit le groupe.', selector: '.group-selector' },
                { title: 'Style', body: 'Teste un modèle, change couleurs et motif, puis observe l’aperçu.', selector: '#builder-template' },
                { title: 'Aperçu', body: 'L’aperçu se met à jour en temps réel pendant la saisie.', selector: '#create-preview-card' },
                { title: 'Historique', body: 'Annuler/Rétablir fonctionne sur tes derniers changements.', selector: '#builder-undo' },
                { title: 'Final', body: 'Quand tout est prêt, clique sur Créer la carte.', selector: 'button[type="submit"]' },
            ];

            let index = 0;
            let $highlighted = null;

            const cleanupHighlight = () => {
                if ($highlighted && $highlighted.length) {
                    $highlighted.removeClass('builder-tutorial-highlight');
                }
                $highlighted = null;
            };

            const highlightStep = (step) => {
                cleanupHighlight();
                const $el = $(step.selector).first();
                if ($el.length) {
                    $highlighted = $el;
                    $highlighted.addClass('builder-tutorial-highlight');
                    try {
                        const rect = $el[0].getBoundingClientRect();
                        const top = rect.top + window.scrollY - 120;
                        window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
                    } catch {
                    }
                }
            };

            const $overlay = $('<div class="builder-tutorial-overlay" role="dialog" aria-modal="true"></div>');
            const $card = $('<div class="builder-tutorial-card"></div>');
            const $title = $('<div class="builder-tutorial-title"></div>');
            const $body = $('<div class="builder-tutorial-body"></div>');
            const $actions = $('<div class="builder-tutorial-actions"></div>');

            const $prev = $('<button type="button" class="btn btn-secondary"><i class="ph ph-arrow-left"></i>Précédent</button>');
            const $next = $('<button type="button" class="btn btn-primary">Suivant<i class="ph ph-arrow-right" style="margin-left:8px;"></i></button>');
            const $close = $('<button type="button" class="btn btn-secondary"><i class="ph ph-x"></i>Fermer</button>');

            $actions.append($prev, $close, $next);
            $card.append($title, $body, $actions);
            $overlay.append($card);
            $('body').append($overlay);

            const render = () => {
                const step = steps[index];
                $title.text(`${index + 1}/${steps.length} — ${step.title}`);
                $body.text(step.body);
                $prev.prop('disabled', index === 0);
                $next.text(index === steps.length - 1 ? 'Terminer' : 'Suivant');
                highlightStep(step);
            };

            const close = () => {
                cleanupHighlight();
                $overlay.remove();
                $(document).off('keydown.builderTutorial');
                try {
                    localStorage.setItem(builderTutorialKey, '1');
                } catch {
                }
            };

            $prev.on('click', () => {
                index = Math.max(0, index - 1);
                render();
            });
            $next.on('click', () => {
                if (index >= steps.length - 1) {
                    close();
                    return;
                }
                index += 1;
                render();
            });
            $close.on('click', close);
            $overlay.on('click', (e) => {
                if ($(e.target).is('.builder-tutorial-overlay')) close();
            });

            $(document).on('keydown.builderTutorial', (e) => {
                if (e.key === 'Escape') {
                    close();
                    return;
                }
                if (e.key === 'ArrowLeft') {
                    index = Math.max(0, index - 1);
                    render();
                    return;
                }
                if (e.key === 'ArrowRight' || e.key === 'Enter') {
                    if (index >= steps.length - 1) {
                        close();
                        return;
                    }
                    index += 1;
                    render();
                }
            });

            render();
        };

        if (isBuilderPage) {
            loadBuilderHistory();
            pushBuilderHistory(collectBuilderState());

            const draft = loadBuilderDraft();
            if (draft && draft.state) {
                const looksEmpty = () => {
                    const s = collectBuilderState();
                    const fields = [
                        s.infinitive, s.infinitive_translation, s.example_sentence,
                        s.je, s.tu, s.il_elle_on, s.nous, s.vous, s.ils_elles,
                    ];
                    return fields.every((v) => String(v || '').trim() === '');
                };

                if (looksEmpty()) {
                    builderIsApplying = true;
                    try {
                        setBuilderState(draft.state);
                    } finally {
                        builderIsApplying = false;
                    }
                }
            }

            $('#builder-template').on('change', function () {
                const id = String($(this).val() || '');
                if (id === '') return;
                applyBuilderTemplate(id);
                $(this).val('');
            });

            $('#builder-undo').on('click', function () {
                if (builderUndo.length <= 1) return;
                const current = builderUndo.pop();
                builderRedo.push(current);
                const snapshot = builderUndo[builderUndo.length - 1];
                applyBuilderHistorySnapshot(snapshot);
                updateBuilderHistoryButtons();
                saveBuilderDraft();
                try {
                    localStorage.setItem(builderHistoryKey, JSON.stringify({ undo: builderUndo, redo: builderRedo }));
                } catch {
                }
            });

            $('#builder-redo').on('click', function () {
                if (!builderRedo.length) return;
                const snapshot = builderRedo.pop();
                builderUndo.push(snapshot);
                applyBuilderHistorySnapshot(snapshot);
                updateBuilderHistoryButtons();
                saveBuilderDraft();
                try {
                    localStorage.setItem(builderHistoryKey, JSON.stringify({ undo: builderUndo, redo: builderRedo }));
                } catch {
                }
            });

            $('#builder-tutorial').on('click', function () {
                runBuilderTutorial();
            });

            $('#builder-clear').on('click', function () {
                const ok = window.confirm('Réinitialiser le brouillon ?');
                if (!ok) return;
                builderIsApplying = true;
                try {
                    setBuilderState({
                        infinitive: '',
                        infinitive_translation: '',
                        example_sentence: '',
                        group: '1er',
                        suit: 'spade',
                        theme_color: '#1e3a5f',
                        accent_color: '#5b9bd5',
                        pattern: '',
                        je: '',
                        tu: '',
                        il_elle_on: '',
                        nous: '',
                        vous: '',
                        ils_elles: '',
                    });
                } finally {
                    builderIsApplying = false;
                }
                builderUndo = [];
                builderRedo = [];
                pushBuilderHistory(collectBuilderState());
                saveBuilderDraft();
            });

            try {
                const seen = localStorage.getItem(builderTutorialKey) === '1';
                if (!seen) {
                    window.setTimeout(() => runBuilderTutorial(), 450);
                }
            } catch {
            }
        }

        $infinitiveInput.on('input', suggestConjugations);
        $infinitiveInput.on('blur', function () {
            normalizeInfinitive();
        });

        // Handle radio changes
        $('input[name="group"]').on('change', function () {
            updateThemeColorForGroup();
            syncSuitToGroup();
            suggestConjugations();
            updateCreatePreview();
        });

        $('input[name="suit"], input[name="suit_display"]').on('change', function () {
            updateCreatePreview();
        });

        // Handle legacy select if it exists
        $groupSelect.on('change', function () {
            updateThemeColorForGroup();
            suggestConjugations();
            updateCreatePreview();
        });

        $('#infinitive_translation, #je, #tu, #il_elle_on, #nous, #vous, #ils_elles, #theme_color, #accent_color, #pattern').on('input change', function () {
            updateCreatePreview();
            queueBuilderSave();
        });

        $infinitiveInput.on('input', function () {
            syncSuitToGroup();
            updateCreatePreview();
            queueBuilderSave();
        });

        $infinitiveInput.closest('form').on('submit', function () {
            normalizeInfinitive();
            normalizeField('#infinitive_translation');
            normalizeField('#je');
            normalizeField('#tu');
            normalizeField('#il_elle_on');
            normalizeField('#nous');
            normalizeField('#vous');
            normalizeField('#ils_elles');
        });

        syncSuitToGroup();
        updateCreatePreview();
    }

    if ($('#rami-shadow-presets').length && $('#rami-center-presets').length) {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        const ramiShadowPresets = JSON.parse($('#rami-shadow-presets').attr('data-presets') || '{}');
        const ramiCenterPresets = JSON.parse($('#rami-center-presets').attr('data-presets') || '{}');

        let hasUnsavedChanges = false;
        let isSavingTheme = false;
        const $saveBtn = $('#theme-save');
        const $status = $('#customizer-save-status');
        const $resetBtn = $('#theme-reset');
        const $magicBtn = $('#theme-magic');

        const uiVersion = parseInt($('#theme-ui-version').val() || '1', 10);
        const liveCssVars = {};

        const setSaveUi = (state) => {
            if ($status.length) {
                $status.removeClass('is-unsaved is-saved');
            }

            if (state === 'unsaved') {
                $saveBtn.prop('disabled', false);
                if ($status.length) {
                    $status.text('Modifications non enregistrées').addClass('is-unsaved');
                }
                return;
            }

            if (state === 'saving') {
                $saveBtn.prop('disabled', true);
                if ($status.length) $status.text('Enregistrement…');
                return;
            }

            if (state === 'saved') {
                $saveBtn.prop('disabled', true);
                if ($status.length) $status.text('Enregistré').addClass('is-saved');
                return;
            }

            $saveBtn.prop('disabled', true);
            if ($status.length) $status.text('Aucune modification');
        };

        const markUnsaved = () => {
            hasUnsavedChanges = true;
            setSaveUi('unsaved');
        };

        if (uiVersion >= 5) {
            // En V5+, on permet l’enregistrement immédiat même sans modification détectée
            // pour éviter un bouton bloqué selon les presets dynamiques
            $saveBtn.prop('disabled', false);
            if ($status.length) {
                $status.text('Prêt à enregistrer');
            }
        }
        const $controlsPanel = $('.customizer-controls');

        const rebuildControlSectionBodies = () => {
            $('.control-section').each(function () {
                const $section = $(this);
                if ($section.find('> .control-section-body').length) return;

                const $title = $section.find('h3').first();
                if (!$title.length) return;

                const $body = $('<div class="control-section-body"></div>');
                $title.nextAll().appendTo($body);
                $section.append($body);

                $title.attr('role', 'button').attr('tabindex', '0');
                $title.on('click', function () {
                    $section.toggleClass('is-collapsed');
                });
                $title.on('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        $section.toggleClass('is-collapsed');
                    }
                });
            });
        };

        const populateJumpMenu = () => {
            const $jump = $('#customizer-jump');
            if (!$jump.length) return;

            $jump.find('option:not(:first)').remove();
            $('.control-section h3').each(function () {
                const title = String($(this).text() || '').trim();
                if (!title) return;
                $jump.append(`<option value="${title}">${title}</option>`);
            });
        };

        const scrollToSectionTitle = (title) => {
            const targetTitle = String(title || '').trim();
            if (!targetTitle) return;

            const $target = $('.control-section h3').filter(function () {
                return String($(this).text() || '').trim() === targetTitle;
            }).first();

            if (!$target.length) return;

            const $section = $target.closest('.control-section');
            $section.removeClass('is-collapsed');

            if ($controlsPanel.length) {
                const top = $controlsPanel.scrollTop() + $section.position().top - 12;
                $controlsPanel.stop(true).animate({ scrollTop: top }, 200);
            } else {
                $section.get(0).scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        };

        const initCustomizerTools = () => {
            rebuildControlSectionBodies();
            populateJumpMenu();

            $('#customizer-expand-all').on('click', function () {
                $('.control-section').removeClass('is-collapsed');
            });

            $('#customizer-collapse-all').on('click', function () {
                $('.control-section').addClass('is-collapsed');
            });

            $('#customizer-jump').on('change', function () {
                scrollToSectionTitle($(this).val());
                $(this).val('');
            });

            $('#customizer-search').on('input', function () {
                const query = String($(this).val() || '').trim().toLowerCase();
                $('#customizer-search-clear').toggleClass('is-visible', query.length > 0);
                if (!query) {
                    $('.control-section').show();
                    return;
                }

                $('.control-section').each(function () {
                    const $section = $(this);
                    const text = $section.text().toLowerCase();
                    $section.toggle(text.includes(query));
                });
            });

            $('#customizer-search-clear').on('click', function () {
                const $search = $('#customizer-search');
                $search.val('').trigger('input');
                $search.trigger('focus');
            });

            $('#customizer-search').trigger('input');
        };

        initCustomizerTools();
        setSaveUi('idle');

        function applyCssVar(cssVar, value) {
            document.querySelectorAll('.rami-card, .rami-card-large, .print-rami-card').forEach((el) => {
                el.style.setProperty(cssVar, value);
            });
            // Mémorise la valeur appliquée pour la sauvegarde exhaustive
            if (typeof cssVar === 'string') {
                liveCssVars[cssVar] = String(value);
            }
            markUnsaved();
        }

        const applyAiSettingsToCustomizer = (settings) => {
            if (!settings || typeof settings !== 'object') return;

            const escapeCssValue = (value) => {
                if (window.CSS && typeof window.CSS.escape === 'function') {
                    return window.CSS.escape(value);
                }
                return String(value || '').replace(/["\\]/g, '\\$&');
            };

            let didApply = false;

            for (const cssVar in settings) {
                if (!Object.prototype.hasOwnProperty.call(settings, cssVar)) continue;
                if (typeof cssVar !== 'string') continue;

                const rawValue = settings[cssVar];
                const value = String(rawValue == null ? '' : rawValue).trim();
                if (!value) continue;

                applyCssVar(cssVar, value);

                const selector = `[data-css-var="${escapeCssValue(cssVar)}"]`;
                const controls = document.querySelectorAll(selector);
                if (!controls.length) {
                    didApply = true;
                    continue;
                }

                controls.forEach((el) => {
                    if (!(el instanceof HTMLElement)) return;

                    const tag = el.tagName.toLowerCase();
                    const type = tag === 'input' ? String(el.getAttribute('type') || '').toLowerCase() : '';

                    if (tag === 'select') {
                        el.value = value;
                        el.dispatchEvent(new Event('change', { bubbles: true }));
                        didApply = true;
                        return;
                    }

                    if (tag === 'textarea') {
                        el.value = value;
                        el.dispatchEvent(new Event('input', { bubbles: true }));
                        didApply = true;
                        return;
                    }

                    if (tag !== 'input') {
                        return;
                    }

                    if (type === 'range') {
                        const numeric = parseFloat(value);
                        if (Number.isNaN(numeric)) return;

                        el.value = String(numeric);
                        el.dispatchEvent(new Event('input', { bubbles: true }));
                        didApply = true;
                        return;
                    }

                    if (type === 'color') {
                        const isHex = /^#(?:[0-9a-f]{3}|[0-9a-f]{6})$/i.test(value);
                        if (isHex) {
                            el.value = value;
                            el.dispatchEvent(new Event('input', { bubbles: true }));
                            didApply = true;
                            return;
                        }

                        const wrapper = el.closest('.color-input-wrapper');
                        const hexField = wrapper ? wrapper.querySelector('input.color-hex') : null;
                        if (hexField) {
                            hexField.value = value;
                        }
                        didApply = true;
                        return;
                    }

                    el.value = value;

                    if (type === 'hidden') {
                        didApply = true;
                        return;
                    }

                    el.dispatchEvent(new Event('input', { bubbles: true }));
                    didApply = true;
                });
            }

            if (didApply) {
                markUnsaved();
            }
        };

        document.addEventListener('theme-ai-apply', (event) => {
            const settings = event && event.detail ? event.detail.settings : null;
            applyAiSettingsToCustomizer(settings);
        });

        $('input[type="color"]').on('input', function () {
            const hex = $(this).val();
            $(this).siblings('.color-hex').val(hex);
            const cssVar = $(this).data('css-var');
            if (cssVar) {
                applyCssVar(cssVar, hex);
            }
            markUnsaved();
        });

        $('input[type="text"][data-css-var]').on('input', function () {
            const cssVar = $(this).data('css-var');
            if (!cssVar) return;
            const raw = String($(this).val() || '').trim();
            applyCssVar(cssVar, raw);

            if ($(this).hasClass('color-hex')) {
                const isHex = /^#(?:[0-9a-f]{3}|[0-9a-f]{6})$/i.test(raw);
                const $picker = $(this).siblings('input[type="color"][data-css-var]');
                if ($picker.length) {
                    if (isHex) {
                        $picker.val(raw);
                    }
                }
            }
            markUnsaved();
        });

        $('input[type="text"].color-hex[data-css-var]').each(function () {
            const raw = String($(this).val() || '').trim();
            const isHex = /^#(?:[0-9a-f]{3}|[0-9a-f]{6})$/i.test(raw);
            const $picker = $(this).siblings('input[type="color"][data-css-var]');
            if ($picker.length) {
                if (isHex) {
                    $picker.val(raw);
                }
            }
        });

        const syncFontOptionsActive = () => {
            const $fontSelect = $('select[data-css-var="--rami-font-family"]');
            if (!$fontSelect.length) return;
            const current = String($fontSelect.val() || '');
            $('#rami-font-options .font-option').each(function () {
                const btnValue = String($(this).data('font-value') || '');
                $(this).toggleClass('active', btnValue === current);
            });
        };

        $('select[data-css-var]').on('change', function () {
            const cssVar = $(this).data('css-var');
            if (!cssVar) return;
            applyCssVar(cssVar, $(this).val());
            if (cssVar === '--rami-font-family') {
                syncFontOptionsActive();
            }
            markUnsaved();
        });

        $('#rami-font-options .font-option').on('click', function () {
            const value = $(this).data('font-value');
            const $fontSelect = $('select[data-css-var="--rami-font-family"]');
            if (!$fontSelect.length) return;
            $fontSelect.val(value).trigger('change');
        });

        syncFontOptionsActive();

        const setSliderProgress = (input) => {
            const min = Number(input.min || 0);
            const max = Number(input.max || 100);
            const val = Number(input.value || 0);
            const pct = (max > min) ? ((val - min) / (max - min)) * 100 : 0;
            input.style.setProperty('--slider-pct', `${Math.min(100, Math.max(0, pct))}%`);
        };

        $('.slider').on('input', function () {
            const val = $(this).val();
            const unit = $(this).data('unit') || '';
            const fullValue = val + unit;
            $(this).siblings('.slider-value').text(fullValue);
            setSliderProgress(this);

            const cssVar = $(this).data('css-var');
            if (cssVar) {
                applyCssVar(cssVar, fullValue);
            }
            markUnsaved();
        });

        $('.slider').each(function () {
            setSliderProgress(this);
        });

        function selectShadowPreset(presetKey) {
            const preset = ramiShadowPresets[presetKey];
            if (!preset) return;

            $('#rami_card_shadow').val(preset.card);
            $('#rami_card_shadow_hover').val(preset.cardHover);

            applyCssVar('--rami-card-shadow', preset.card);
            applyCssVar('--rami-card-shadow-hover', preset.cardHover);

            $('[data-rami-shadow-preset]').removeClass('active');
            $(`.shadow-option[data-rami-shadow-preset="${presetKey}"]`).addClass('active');
        }

        $('.shadow-option[data-rami-shadow-preset]').on('click', function () {
            const presetKey = $(this).data('rami-shadow-preset');
            selectShadowPreset(presetKey);
            markUnsaved();
        });

        const currentShadow = $('#rami_card_shadow').val();
        const currentShadowHover = $('#rami_card_shadow_hover').val();
        const matchingPreset = Object.keys(ramiShadowPresets).find((key) => {
            const preset = ramiShadowPresets[key];
            return preset && preset.card === currentShadow && preset.cardHover === currentShadowHover;
        });

        selectShadowPreset(matchingPreset || 'default');

        const ramiBackgroundPresets = {
            premium: {
                pattern: 'premium',
                patternColor: 'rgba(212, 175, 55, 0.08)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.01',
            },
            royal: {
                pattern: 'royal',
                patternColor: 'rgba(246, 231, 178, 0.08)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.01',
            },
            'mandala-noir-or': {
                pattern: 'mandala-noir-or',
                patternColor: 'rgba(212, 175, 55, 0.55)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.01',
            },
            'mandala-nuit-or': {
                pattern: 'mandala-nuit-or',
                patternColor: 'rgba(212, 175, 55, 0.55)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.01',
            },
            'mandala-bordeaux-or': {
                pattern: 'mandala-bordeaux-or',
                patternColor: 'rgba(212, 175, 55, 0.6)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.01',
            },
            'mandala-noir-coeurs-or': {
                pattern: 'mandala-noir-coeurs-or',
                patternColor: 'rgba(212, 175, 55, 0.55)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.01',
            },
            briare: {
                pattern: 'briare',
                patternColor: 'rgba(212, 175, 55, 0.35)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.01',
            },
            'artdeco-paris': {
                pattern: 'artdeco-paris',
                patternColor: 'rgba(212, 175, 55, 0.28)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.01',
            },
            provence: {
                pattern: 'provence',
                patternColor: 'rgba(201, 135, 82, 0.25)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.01',
            },
            cloisonne: {
                pattern: 'cloisonne',
                patternColor: 'rgba(212, 175, 55, 0.32)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.01',
            },
            parametric: {
                pattern: 'parametric',
                patternColor: 'rgba(91, 155, 213, 0.2)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.01',
            },
            plain: {
                pattern: 'plain',
                patternColor: 'rgba(30, 58, 95, 0)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0',
            },
            diamonds: {
                pattern: 'diamonds',
                patternColor: 'rgba(30, 58, 95, 0.03)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.03',
            },
            geometry: {
                pattern: 'geometry',
                patternColor: 'rgba(30, 58, 95, 0)',
                circlesStrength: '14%',
                rectanglesStrength: '10%',
                noiseOpacity: '0.02',
            },
            mixed: {
                pattern: 'mixed',
                patternColor: 'rgba(30, 58, 95, 0.03)',
                circlesStrength: '12%',
                rectanglesStrength: '9%',
                noiseOpacity: '0.03',
            },
            checker: {
                pattern: 'checker',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            stripes: {
                pattern: 'stripes',
                patternColor: 'rgba(30, 58, 95, 0.03)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            dots: {
                pattern: 'dots',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            hexagons: {
                pattern: 'hexagons',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            triangles: {
                pattern: 'triangles',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            chevron: {
                pattern: 'chevron',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            waves: {
                pattern: 'waves',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            scales: {
                pattern: 'scales',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            halfcircles: {
                pattern: 'halfcircles',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            grid: {
                pattern: 'grid',
                patternColor: 'rgba(30, 58, 95, 0.03)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            zigzag: {
                pattern: 'zigzag',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            crosses: {
                pattern: 'crosses',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            graph: {
                pattern: 'graph',
                patternColor: 'rgba(30, 58, 95, 0.03)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            crosshatch: {
                pattern: 'crosshatch',
                patternColor: 'rgba(30, 58, 95, 0.035)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            bricks: {
                pattern: 'bricks',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            stars: {
                pattern: 'stars',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            mosaic: {
                pattern: 'mosaic',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            circles: {
                pattern: 'circles',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            minimal: {
                pattern: 'minimal',
                patternColor: 'rgba(30, 58, 95, 0.035)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            baroque: {
                pattern: 'baroque',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            ornate: {
                pattern: 'ornate',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            weave: {
                pattern: 'weave',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            artdeco: {
                pattern: 'artdeco',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            herringbone: {
                pattern: 'herringbone',
                patternColor: 'rgba(30, 58, 95, 0.045)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            lace: {
                pattern: 'lace',
                patternColor: 'rgba(30, 58, 95, 0.045)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            origami: {
                pattern: 'origami',
                patternColor: 'rgba(30, 58, 95, 0.045)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            circuit: {
                pattern: 'circuit',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            constellation: {
                pattern: 'constellation',
                patternColor: 'rgba(30, 58, 95, 0.045)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            stainedglass: {
                pattern: 'stainedglass',
                patternColor: 'rgba(30, 58, 95, 0.055)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            foliage: {
                pattern: 'foliage',
                patternColor: 'rgba(30, 58, 95, 0.045)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            arabesque: {
                pattern: 'arabesque',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            damask: {
                pattern: 'damask',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            paisley: {
                pattern: 'paisley',
                patternColor: 'rgba(30, 58, 95, 0.06)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            celtic: {
                pattern: 'celtic',
                patternColor: 'rgba(30, 58, 95, 0.055)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            'japanese-wave': {
                pattern: 'japanese-wave',
                patternColor: 'rgba(30, 58, 95, 0.055)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            moroccan: {
                pattern: 'moroccan',
                patternColor: 'rgba(30, 58, 95, 0.06)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            artnouveau: {
                pattern: 'artnouveau',
                patternColor: 'rgba(30, 58, 95, 0.055)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            nordic: {
                pattern: 'nordic',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            quilted: {
                pattern: 'quilted',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            byzantine: {
                pattern: 'byzantine',
                patternColor: 'rgba(30, 58, 95, 0.06)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            bamboo: {
                pattern: 'bamboo',
                patternColor: 'rgba(30, 58, 95, 0.055)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            feather: {
                pattern: 'feather',
                patternColor: 'rgba(30, 58, 95, 0.055)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            crystalline: {
                pattern: 'crystalline',
                patternColor: 'rgba(30, 58, 95, 0.06)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            terrazzo: {
                pattern: 'terrazzo',
                patternColor: 'rgba(30, 58, 95, 0.06)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            labyrinth: {
                pattern: 'labyrinth',
                patternColor: 'rgba(30, 58, 95, 0.055)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            tribal: {
                pattern: 'tribal',
                patternColor: 'rgba(30, 58, 95, 0.06)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            nautical: {
                pattern: 'nautical',
                patternColor: 'rgba(30, 58, 95, 0.055)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            mandala: {
                pattern: 'mandala',
                patternColor: 'rgba(30, 58, 95, 0.06)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            topographic: {
                pattern: 'topographic',
                patternColor: 'rgba(30, 58, 95, 0.055)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            starmap: {
                pattern: 'starmap',
                patternColor: 'rgba(30, 58, 95, 0.06)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            renaissance: {
                pattern: 'renaissance',
                patternColor: 'rgba(30, 58, 95, 0.06)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            'diagonal-checker': {
                pattern: 'diagonal-checker',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            ticks: {
                pattern: 'ticks',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            fleurdelys: {
                pattern: 'fleurdelys',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            ikat: {
                pattern: 'ikat',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            marble: {
                pattern: 'marble',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            houndstooth: {
                pattern: 'houndstooth',
                patternColor: 'rgba(30, 58, 95, 0.045)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            toiledejouy: {
                pattern: 'toiledejouy',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            seigaiha: {
                pattern: 'seigaiha',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            broderie: {
                pattern: 'broderie',
                patternColor: 'rgba(30, 58, 95, 0.055)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            guilloche: {
                pattern: 'guilloche',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            tapisserie: {
                pattern: 'tapisserie',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            liberty: {
                pattern: 'liberty',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            chevronfr: {
                pattern: 'chevronfr',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            pointhongrie: {
                pattern: 'pointhongrie',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            versailles: {
                pattern: 'versailles',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            brocart: {
                pattern: 'brocart',
                patternColor: 'rgba(30, 58, 95, 0.04)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            mosaicfr: {
                pattern: 'mosaicfr',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            ecailles: {
                pattern: 'ecailles',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            coquille: {
                pattern: 'coquille',
                patternColor: 'rgba(30, 58, 95, 0.05)',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0.02',
            },
            'classic-red': {
                pattern: 'classic-red',
                patternColor: 'transparent',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0',
            },
            'classic-blue': {
                pattern: 'classic-blue',
                patternColor: 'transparent',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0',
            },
            'classic-gold': {
                pattern: 'classic-gold',
                patternColor: 'transparent',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0',
            },
            'classic-emerald': {
                pattern: 'classic-emerald',
                patternColor: 'transparent',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0',
            },
            'filigrane-red': {
                pattern: 'filigrane-red',
                patternColor: 'transparent',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0',
            },
            'filigrane-blue': {
                pattern: 'filigrane-blue',
                patternColor: 'transparent',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0',
            },
            'ecusson-red': {
                pattern: 'ecusson-red',
                patternColor: 'transparent',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0',
            },
            'ecusson-blue': {
                pattern: 'ecusson-blue',
                patternColor: 'transparent',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0',
            },
            'eventail-red': {
                pattern: 'eventail-red',
                patternColor: 'transparent',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0',
            },
            'eventail-blue': {
                pattern: 'eventail-blue',
                patternColor: 'transparent',
                circlesStrength: '0%',
                rectanglesStrength: '0%',
                noiseOpacity: '0',
            },
        };

        function selectBackgroundPreset(presetKey) {
            const preset = ramiBackgroundPresets[presetKey];
            if (!preset) return;

            // Apply data-pattern attribute
            const patternName = preset.pattern || 'plain';
            $('.rami-card, .rami-card-large, .print-rami-card').attr('data-pattern', patternName);

            const circlesStrengthNumber = parseFloat(String(preset.circlesStrength).replace('%', ''));
            const rectanglesStrengthNumber = parseFloat(String(preset.rectanglesStrength).replace('%', ''));

            const $patternColorInput = $('[data-css-var="--rami-pattern-color"]');
            $patternColorInput.val(preset.patternColor);
            applyCssVar('--rami-pattern-color', preset.patternColor);

            const $selectedPatternInput = $('[data-css-var="--rami-selected-pattern"]');
            if ($selectedPatternInput.length) {
                $selectedPatternInput.val(patternName);
            }
            applyCssVar('--rami-selected-pattern', patternName);

            if (!Number.isNaN(circlesStrengthNumber)) {
                $('[data-css-var="--rami-bg-circles-strength"]').val(String(circlesStrengthNumber));
            }
            if (!Number.isNaN(rectanglesStrengthNumber)) {
                $('[data-css-var="--rami-bg-rectangles-strength"]').val(String(rectanglesStrengthNumber));
            }

            const $noiseOpacitySlider = $('[data-css-var="--rami-noise-opacity"]');
            $noiseOpacitySlider.val(preset.noiseOpacity);

            applyCssVar('--rami-bg-circles-strength', preset.circlesStrength);
            applyCssVar('--rami-bg-rectangles-strength', preset.rectanglesStrength);
            applyCssVar('--rami-noise-opacity', preset.noiseOpacity);

            const $circlesVal = $('[data-css-var="--rami-bg-circles-strength"]').siblings('.slider-value');
            if ($circlesVal.length) $circlesVal.text(preset.circlesStrength);

            const $rectanglesVal = $('[data-css-var="--rami-bg-rectangles-strength"]').siblings('.slider-value');
            if ($rectanglesVal.length) $rectanglesVal.text(preset.rectanglesStrength);

            const $noiseVal = $noiseOpacitySlider.siblings('.slider-value');
            if ($noiseVal.length) $noiseVal.text(preset.noiseOpacity);

            $('[data-rami-bg-preset]').removeClass('active');
            $(`.shadow-option[data-rami-bg-preset="${presetKey}"]`).addClass('active');
        }

        $('.shadow-option[data-rami-bg-preset]').on('click', function () {
            const presetKey = $(this).data('rami-bg-preset');
            selectBackgroundPreset(presetKey);
            markUnsaved();
        });

        function selectCardBackPreset(presetKey) {
            const preset = ramiBackgroundPresets[presetKey];
            if (!preset) return;

            const patternName = preset.pattern || 'plain';
            $('.rami-card-back, .print-rami-card-back').attr('data-pattern', patternName);

            const $selectedPatternInput = $('[data-css-var="--rami-card-back-pattern"]');
            if ($selectedPatternInput.length) {
                $selectedPatternInput.val(patternName);
            }
            applyCssVar('--rami-card-back-pattern', patternName);

            $('[data-rami-card-back-preset]').removeClass('active');
            $(`.shadow-option[data-rami-card-back-preset="${presetKey}"]`).addClass('active');
        }

        $('.shadow-option[data-rami-card-back-preset]').on('click', function () {
            const presetKey = $(this).data('rami-card-back-preset');
            selectCardBackPreset(presetKey);
            markUnsaved();
        });

        const currentPatternColor = $('[data-css-var="--rami-pattern-color"]').val();
        const currentCirclesStrength = $('[data-css-var="--rami-bg-circles-strength"]').val();
        const currentRectanglesStrength = $('[data-css-var="--rami-bg-rectangles-strength"]').val();
        const currentNoiseOpacity = $('[data-css-var="--rami-noise-opacity"]').val();
        const currentCirclesStrengthFull = `${currentCirclesStrength}${$('[data-css-var="--rami-bg-circles-strength"]').data('unit') || ''}`;
        const currentRectanglesStrengthFull = `${currentRectanglesStrength}${$('[data-css-var="--rami-bg-rectangles-strength"]').data('unit') || ''}`;
        const matchingBackgroundPreset = Object.keys(ramiBackgroundPresets).find((key) => {
            const preset = ramiBackgroundPresets[key];
            return preset
                && preset.patternColor === currentPatternColor
                && preset.circlesStrength === currentCirclesStrengthFull
                && preset.rectanglesStrength === currentRectanglesStrengthFull
                && preset.noiseOpacity === currentNoiseOpacity;
        });

        $('[data-rami-bg-preset]').removeClass('active');
        if (matchingBackgroundPreset) {
            $(`.shadow-option[data-rami-bg-preset="${matchingBackgroundPreset}"]`).addClass('active');
        }

        const applyStoredPattern = () => {
            const $patternInput = $('[data-css-var="--rami-selected-pattern"]');
            let pattern = String($patternInput.val() || '').trim();

            if (!pattern) {
                const element = document.querySelector('.rami-card') || document.querySelector('.rami-card-large') || document.querySelector('.print-rami-card');
                if (element) {
                    pattern = String(window.getComputedStyle(element).getPropertyValue('--rami-selected-pattern') || '').trim();
                }
            }

            if (!pattern) pattern = 'plain';
            $('.rami-card, .rami-card-large, .print-rami-card').attr('data-pattern', pattern);

            if (Object.prototype.hasOwnProperty.call(ramiBackgroundPresets, pattern)) {
                $('[data-rami-bg-preset]').removeClass('active');
                $(`.shadow-option[data-rami-bg-preset="${pattern}"]`).addClass('active');
            }
        };

        applyStoredPattern();

        const applyStoredCardBackPattern = () => {
            const $patternInput = $('[data-css-var="--rami-card-back-pattern"]');
            let pattern = String($patternInput.val() || '').trim();

            if (!pattern) {
                const element = document.querySelector('.rami-card-back') || document.querySelector('.rami-card') || document.querySelector('.print-rami-card');
                if (element) {
                    pattern = String(window.getComputedStyle(element).getPropertyValue('--rami-card-back-pattern') || '').trim();
                }
            }

            if (!pattern) pattern = 'diamonds';
            $('.rami-card-back, .print-rami-card-back').attr('data-pattern', pattern);

            if (Object.prototype.hasOwnProperty.call(ramiBackgroundPresets, pattern)) {
                $('[data-rami-card-back-preset]').removeClass('active');
                $(`.shadow-option[data-rami-card-back-preset="${pattern}"]`).addClass('active');
            }
        };

        applyStoredCardBackPattern();

        const setIllustrationDesign = (designKey) => {
            const design = String(designKey || '').trim() || 'none';

            const $targets = $('.rami-card-illustration, .rami-large-illustration-frame');
            if (design === 'none') {
                $targets.removeAttr('data-illustration-design');
            } else {
                $targets.attr('data-illustration-design', design);
            }

            $('.design-option[data-rami-illustration-design]').removeClass('active');
            const $activeOption = $(`.design-option[data-rami-illustration-design="${design}"]`).addClass('active');
            const activeEl = $activeOption.get(0);
            if (activeEl && typeof activeEl.scrollIntoView === 'function') {
                const prefersReducedMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                activeEl.scrollIntoView({
                    behavior: prefersReducedMotion ? 'auto' : 'smooth',
                    block: 'nearest',
                    inline: 'nearest'
                });
            }
        };

        let initialDesign = String($('#rami_illustration_design').val() || '').trim();
        if (!initialDesign) {
            const element = document.querySelector('.rami-card') || document.querySelector('.rami-card-large') || document.querySelector('.print-rami-card');
            if (element) {
                initialDesign = String(window.getComputedStyle(element).getPropertyValue('--rami-illustration-design') || '').trim();
            }
        }
        if (!initialDesign) initialDesign = 'none';
        setIllustrationDesign(initialDesign);

        $('.design-option[data-rami-illustration-design]').on('click', function () {
            const designKey = $(this).data('rami-illustration-design');
            $('#rami_illustration_design').val(designKey);
            setIllustrationDesign(designKey);
        });

        const getDesignCategory = (designKey) => {
            const key = String(designKey || '').trim();
            if (key.startsWith('gradient-')) return 'gradient';
            if (key.startsWith('pattern-')) return 'pattern';
            if (key.startsWith('texture-')) return 'texture';
            if (key.startsWith('glass-')) return 'glass';
            if (key.startsWith('neon-') || key.startsWith('glow-')) return 'neon';
            if (key.startsWith('metal-')) return 'metal';
            if (key.startsWith('nature-')) return 'nature';
            if (key.startsWith('geo-')) return 'geo';
            if (key.startsWith('art-')) return 'art';
            if (key.startsWith('future-')) return 'future';
            if (key.startsWith('retro-')) return 'retro';
            return 'other';
        };

        const applyDesignFilter = () => {
            const $category = $('#design-category-filter');
            const $search = $('#design-search-filter');
            if (!$category.length && !$search.length) return;

            const selectedCategory = String($category.val() || 'all').trim();
            const query = String($search.val() || '').trim().toLowerCase();
            const $active = $('.design-option.active');

            $('.design-option[data-rami-illustration-design]').each(function () {
                const $btn = $(this);
                const designKey = String($btn.data('rami-illustration-design') || '').trim();
                const category = getDesignCategory(designKey);
                const label = String($btn.find('span').first().text() || '').trim().toLowerCase();
                const keyText = designKey.toLowerCase();

                const matchesCategory = selectedCategory === 'all' || category === selectedCategory || designKey === 'none';
                const matchesQuery = !query || label.includes(query) || keyText.includes(query);

                $btn.toggle(matchesCategory && matchesQuery);
            });

            if ($active.length) $active.show();
        };

        $('#design-category-filter').on('change', applyDesignFilter);
        $('#design-search-filter').on('input', applyDesignFilter);
        $('#design-filter-clear').on('click', function () {
            $('#design-category-filter').val('all');
            $('#design-search-filter').val('');
            applyDesignFilter();
        });

        applyDesignFilter();

        function selectCenterPreset(presetKey) {
            const preset = ramiCenterPresets[presetKey];
            if (!preset) return;

            const illustrationInnerInset = preset.illustrationInnerInset || '0px';
            const illustrationInnerBgStart = preset.illustrationInnerBgStart || 'rgba(255, 255, 255, 0)';
            const illustrationInnerBgEnd = preset.illustrationInnerBgEnd || 'rgba(255, 255, 255, 0)';
            const illustrationClipPath = preset.illustrationClipPath || 'none';

            $('[data-rami-center-preset]').removeClass('active');
            $(`[data-rami-center-preset="${presetKey}"]`).addClass('active');

            const sizeNumber = parseFloat(String(preset.illustrationSize).replace('px', ''));
            const borderNumber = parseFloat(String(preset.illustrationBorderWidth).replace('px', ''));
            const innerInsetNumber = parseFloat(String(illustrationInnerInset).replace('px', ''));

            if (!Number.isNaN(sizeNumber)) {
                $('[data-css-var="--rami-illustration-size"]').val(String(sizeNumber));
            }
            $('[data-css-var="--rami-illustration-radius"]').val(preset.illustrationRadius);
            if (!Number.isNaN(borderNumber)) {
                $('[data-css-var="--rami-illustration-border-width"]').val(String(borderNumber));
            }
            if (!Number.isNaN(innerInsetNumber)) {
                $('[data-css-var="--rami-illustration-inner-inset"]').val(String(innerInsetNumber));
            }
            $('[data-css-var="--rami-illustration-inner-bg-start"]').val(illustrationInnerBgStart);
            $('[data-css-var="--rami-illustration-inner-bg-end"]').val(illustrationInnerBgEnd);
            /* Handle clip-path input if it exists, otherwise it will be ignored */
            const $clipInput = $('[data-css-var="--rami-illustration-clip-path"]');
            if ($clipInput.length) $clipInput.val(illustrationClipPath);

            $('#rami_illustration_shadow').val(preset.illustrationShadow);

            applyCssVar('--rami-illustration-size', preset.illustrationSize);
            applyCssVar('--rami-illustration-radius', preset.illustrationRadius);
            applyCssVar('--rami-illustration-border-width', preset.illustrationBorderWidth);
            applyCssVar('--rami-illustration-shadow', preset.illustrationShadow);
            applyCssVar('--rami-illustration-inner-inset', illustrationInnerInset);
            applyCssVar('--rami-illustration-inner-bg-start', illustrationInnerBgStart);
            applyCssVar('--rami-illustration-inner-bg-end', illustrationInnerBgEnd);
            applyCssVar('--rami-illustration-clip-path', illustrationClipPath);

            const $sizeVal = $('[data-css-var="--rami-illustration-size"]').siblings('.slider-value');
            if ($sizeVal.length) $sizeVal.text(preset.illustrationSize);

            const $borderVal = $('[data-css-var="--rami-illustration-border-width"]').siblings('.slider-value');
            if ($borderVal.length) $borderVal.text(preset.illustrationBorderWidth);

            const $innerInsetVal = $('[data-css-var="--rami-illustration-inner-inset"]').siblings('.slider-value');
            if ($innerInsetVal.length) $innerInsetVal.text(illustrationInnerInset);
        }

        $('.shadow-option[data-rami-center-preset]').on('click', function () {
            const presetKey = $(this).data('rami-center-preset');
            selectCenterPreset(presetKey);
            markUnsaved();
        });

        const currentIllustrationSize = $('[data-css-var="--rami-illustration-size"]').val();
        const currentIllustrationRadius = $('[data-css-var="--rami-illustration-radius"]').val();
        const currentIllustrationBorderWidth = $('[data-css-var="--rami-illustration-border-width"]').val();
        const currentIllustrationInnerInset = $('[data-css-var="--rami-illustration-inner-inset"]').val();
        const currentIllustrationInnerBgStart = $('[data-css-var="--rami-illustration-inner-bg-start"]').val();
        const currentIllustrationInnerBgEnd = $('[data-css-var="--rami-illustration-inner-bg-end"]').val();
        const currentIllustrationClipPath = $('[data-css-var="--rami-illustration-clip-path"]').val() || 'none';
        const currentIllustrationShadow = $('#rami_illustration_shadow').val();
        const currentIllustrationSizeFull = `${currentIllustrationSize}${$('[data-css-var="--rami-illustration-size"]').data('unit') || ''}`;
        const currentIllustrationBorderWidthFull = `${currentIllustrationBorderWidth}${$('[data-css-var="--rami-illustration-border-width"]').data('unit') || ''}`;
        const currentIllustrationInnerInsetFull = `${currentIllustrationInnerInset}${$('[data-css-var="--rami-illustration-inner-inset"]').data('unit') || ''}`;
        const matchingCenterPreset = Object.keys(ramiCenterPresets).find((key) => {
            const preset = ramiCenterPresets[key];
            const illustrationInnerInset = preset && preset.illustrationInnerInset ? preset.illustrationInnerInset : '0px';
            const illustrationInnerBgStart = preset && preset.illustrationInnerBgStart ? preset.illustrationInnerBgStart : 'rgba(255, 255, 255, 0)';
            const illustrationInnerBgEnd = preset && preset.illustrationInnerBgEnd ? preset.illustrationInnerBgEnd : 'rgba(255, 255, 255, 0)';
            const illustrationClipPath = preset && preset.illustrationClipPath ? preset.illustrationClipPath : 'none';
            return preset
                && preset.illustrationSize === currentIllustrationSizeFull
                && preset.illustrationRadius === currentIllustrationRadius
                && preset.illustrationBorderWidth === currentIllustrationBorderWidthFull
                && preset.illustrationShadow === currentIllustrationShadow
                && illustrationInnerInset === currentIllustrationInnerInsetFull
                && illustrationInnerBgStart === currentIllustrationInnerBgStart
                && illustrationInnerBgEnd === currentIllustrationInnerBgEnd
                && illustrationClipPath === currentIllustrationClipPath;
        });

        $('[data-rami-center-preset]').removeClass('active');
        $(`[data-rami-center-preset="${matchingCenterPreset || 'circle'}"]`).addClass('active');

        const resetPreviewFit = () => {
            document.querySelectorAll('.preview-cards-grid, .preview-mode .rami-card-large').forEach((el) => {
                el.style.transform = '';
                el.style.transformOrigin = '';
            });
        };

        const fitPreviewToContainer = () => {
            const container = document.querySelector('.preview-container');
            if (!container) return;
            if (document.body.classList.contains('is-preview-printing')) {
                resetPreviewFit();
                return;
            }

            const activeMode = container.querySelector('.preview-mode.active');
            if (!activeMode) return;

            const target = activeMode.querySelector('.rami-card-large') || activeMode.querySelector('.preview-cards-grid');
            if (!target) return;

            target.style.transform = '';
            target.style.transformOrigin = '';

            const containerRect = container.getBoundingClientRect();
            const styles = window.getComputedStyle(container);
            const paddingX = (parseFloat(styles.paddingLeft) || 0) + (parseFloat(styles.paddingRight) || 0);
            const paddingY = (parseFloat(styles.paddingTop) || 0) + (parseFloat(styles.paddingBottom) || 0);
            const availableW = Math.max(0, containerRect.width - paddingX);
            const availableH = Math.max(0, containerRect.height - paddingY);

            const targetRect = target.getBoundingClientRect();
            if (targetRect.width <= 0 || targetRect.height <= 0) return;

            const rawScale = Math.min(availableW / targetRect.width, availableH / targetRect.height);
            const scale = Math.max(0.6, Math.min(rawScale, 1));

            target.style.transformOrigin = 'center center';
            target.style.transform = `scale(${scale})`;
        };

        let previewFitRaf = 0;
        const schedulePreviewFit = () => {
            if (previewFitRaf) return;
            previewFitRaf = window.requestAnimationFrame(() => {
                previewFitRaf = 0;
                fitPreviewToContainer();
            });
        };

        $('[data-preview-mode]').on('click', function () {
            const mode = $(this).data('preview-mode');
            if (!mode) return;

            $('[data-preview-mode]').removeClass('active');
            $(this).addClass('active');

            $('.preview-mode').removeClass('active');
            $(`.preview-mode[data-preview="${mode}"]`).addClass('active');
            schedulePreviewFit();
        });

        // Viewport Switcher
        const setPreviewWidthClass = (widthValue) => {
            const width = String(widthValue || '').trim();
            const $container = $('.preview-container');
            if (!$container.length) return;

            $container.removeClass('is-preview-mobile is-preview-tablet is-preview-desktop');

            if (width === '375px') {
                $container.addClass('is-preview-mobile');
                return;
            }
            if (width === '768px') {
                $container.addClass('is-preview-tablet');
                return;
            }

            $container.addClass('is-preview-desktop');
        };

        const applyPreviewWidth = (widthValue) => {
            const width = String(widthValue || '').trim();
            const $container = $('.preview-container');
            if (!$container.length) return;

            if (!width || width === '100%') {
                $container.css({ maxWidth: '', width: '100%' });
                return;
            }

            $container.css({ maxWidth: width, width: '100%' });
        };

        const $themeCustomizer = $('.theme-customizer');
        const forceFullPreview = $themeCustomizer.hasClass('theme-customizer--v2')
            && $('.preview-container').hasClass('preview-container--single');

        const savedPreviewWidth = localStorage.getItem('admin_theme_preview_width');
        if (forceFullPreview) {
            applyPreviewWidth('100%');
            setPreviewWidthClass('100%');
            localStorage.setItem('admin_theme_preview_width', '100%');
            $('[data-preview-width]').removeClass('active');
            $('[data-preview-width="100%"]').addClass('active');
        } else if (savedPreviewWidth) {
            applyPreviewWidth(savedPreviewWidth);
            setPreviewWidthClass(savedPreviewWidth);
            $('[data-preview-width]').removeClass('active');
            $(`[data-preview-width="${savedPreviewWidth}"]`).addClass('active');
        } else {
            applyPreviewWidth('100%');
            setPreviewWidthClass('100%');
        }

        $('[data-preview-width]').on('click', function () {
            const width = $(this).data('preview-width');
            applyPreviewWidth(width);
            setPreviewWidthClass(width);
            localStorage.setItem('admin_theme_preview_width', String(width));

            $('[data-preview-width]').removeClass('active');
            $(this).addClass('active');
            schedulePreviewFit();
        });

        // Dark Mode Preview Toggle
        const savedPreviewDark = localStorage.getItem('admin_theme_preview_dark');
        if (savedPreviewDark === '1') {
            const $container = $('.preview-container');
            $container.addClass('dark-mode');
            $('#preview-theme-toggle').find('i').attr('class', 'ph ph-sun');
        }

        $('#preview-theme-toggle').on('click', function () {
            const $container = $('.preview-container');
            const isDark = $container.hasClass('dark-mode');
            $container.toggleClass('dark-mode');
            $(this).find('i').attr('class', isDark ? 'ph ph-moon' : 'ph ph-sun');
            localStorage.setItem('admin_theme_preview_dark', isDark ? '0' : '1');
        });

        const $previewRoot = $('.customizer-preview');
        const $fullscreenBtn = $('#preview-fullscreen');

        const applyV2ConjugatedVerbsToPreview = () => {
            if (!$themeCustomizer.length) return;
            if (!$themeCustomizer.hasClass('theme-customizer--v2')) return;
            if ($themeCustomizer.hasClass('theme-customizer--v3')) return;

            $('.preview-mode[data-preview="card"] .rami-card, .preview-mode[data-preview="all"] .rami-card').each(function () {
                const $card = $(this);
                const conjugated = String(
                    $card.find('.rami-card-index-top .rami-card-index-verb').first().text()
                    || $card.find('.rami-card-index-verb').first().text()
                    || ''
                ).trim();
                if (!conjugated) return;
                const display = conjugated.toLocaleUpperCase('fr-FR');
                $card.find('.rami-card-verb .rami-card-verb-text').text(display);
            });

            $('.preview-mode[data-preview="large"] .rami-card-large').each(function () {
                const $card = $(this);
                const conjugated = String(
                    $card.find('.rami-large-index-top .rami-large-index-verb').first().text()
                    || $card.find('.rami-large-index-verb').first().text()
                    || ''
                ).trim();
                if (!conjugated) return;
                const display = conjugated.toLocaleUpperCase('fr-FR');
                $card.find('.rami-large-verb-section .rami-large-infinitive').text(display);
            });
        };

        const setPreviewFullscreen = (on) => {
            const enabled = Boolean(on);
            $themeCustomizer.toggleClass('is-preview-fullscreen', enabled);
            $('body').toggleClass('is-no-scroll', enabled);
            $fullscreenBtn.toggleClass('is-active', enabled).attr('aria-pressed', enabled ? 'true' : 'false');
            $fullscreenBtn.attr('title', enabled ? 'Quitter plein écran' : 'Plein écran');
            $fullscreenBtn.find('i').attr('class', enabled ? 'ph ph-arrows-in' : 'ph ph-arrows-out');
            schedulePreviewFit();
        };

        $fullscreenBtn.on('click', function () {
            setPreviewFullscreen(!$themeCustomizer.hasClass('is-preview-fullscreen'));
        });

        const $printBtn = $('#preview-print');
        const setPreviewPrintMode = (on) => {
            const enabled = Boolean(on);
            $themeCustomizer.toggleClass('is-preview-printing', enabled);
            $('body').toggleClass('is-preview-printing', enabled);
            schedulePreviewFit();
        };

        const clearPreviewPrintMode = () => setPreviewPrintMode(false);

        if ($printBtn.length) {
            $printBtn.on('click', function () {
                setPreviewPrintMode(true);
                window.print();
            });
        }

        if (window.matchMedia) {
            const mediaQueryList = window.matchMedia('print');
            if (mediaQueryList.addEventListener) {
                mediaQueryList.addEventListener('change', (event) => {
                    if (!event.matches) {
                        clearPreviewPrintMode();
                    }
                });
            } else if (mediaQueryList.addListener) {
                mediaQueryList.addListener((event) => {
                    if (!event.matches) {
                        clearPreviewPrintMode();
                    }
                });
            }
        }

        if (window.addEventListener) {
            window.addEventListener('afterprint', clearPreviewPrintMode);
        }

        applyV2ConjugatedVerbsToPreview();
        schedulePreviewFit();

        $('#theme-form').on('input change', 'input[data-css-var], select[data-css-var]', function () {
            schedulePreviewFit();
        });

        $(window).on('resize', function () {
            schedulePreviewFit();
        });

        $previewRoot.on('click', function (e) {
            if (!$themeCustomizer.hasClass('is-preview-fullscreen')) return;
            if ($(e.target).closest('.preview-sticky').length) return;
            setPreviewFullscreen(false);
        });

        // Magic Button (Advanced Randomizer)
        $('#theme-magic').on('click', function () {
            if (isSavingTheme) return;
            const btn = $(this);
            btn.addClass('btn-loading');

            // 1. Random Presets (Background, Shadow, Center, Font)
            const bgKeys = Object.keys(ramiBackgroundPresets);
            selectBackgroundPreset(bgKeys[Math.floor(Math.random() * bgKeys.length)]);

            const shadowKeys = Object.keys(ramiShadowPresets);
            selectShadowPreset(shadowKeys[Math.floor(Math.random() * shadowKeys.length)]);

            const centerKeys = Object.keys(ramiCenterPresets);
            selectCenterPreset(centerKeys[Math.floor(Math.random() * centerKeys.length)]);

            const $fontSelect = $('[data-css-var="--rami-font-family"]');
            if ($fontSelect.length) {
                const options = $fontSelect.find('option');
                const randomOption = options.eq(Math.floor(Math.random() * options.length));
                $fontSelect.val(randomOption.val()).trigger('input');
            }

            // 2. Intelligent Color Palettes
            const palettes = [
                { // Soft Paper
                    bg: '#faf8f5', border: '#e6e4e0', accent1: '#1e3a5f', accent2: '#2d5a3d', accent3: '#5a2d5a',
                    illusStart: '#f0efed', illusEnd: '#e8e6e2'
                },
                { // Modern Dark
                    bg: '#1a1f25', border: '#2f3b4b', accent1: '#60a5fa', accent2: '#34d399', accent3: '#a78bfa',
                    illusStart: '#2c333e', illusEnd: '#1a1f25'
                },
                { // Vivid White
                    bg: '#ffffff', border: '#000000', accent1: '#2563eb', accent2: '#16a34a', accent3: '#9333ea',
                    illusStart: '#eff6ff', illusEnd: '#ffffff'
                },
                { // Warm Pastel
                    bg: '#fffbf0', border: '#f0e6d2', accent1: '#f87171', accent2: '#4ade80', accent3: '#60a5fa',
                    illusStart: '#fffbeb', illusEnd: '#fff1f2'
                },
                { // Cool Gray
                    bg: '#f3f4f6', border: '#d1d5db', accent1: '#111827', accent2: '#374151', accent3: '#4b5563',
                    illusStart: '#e5e7eb', illusEnd: '#f9fafb'
                },
                { // Navy Gold
                    bg: '#0f172a', border: '#1e293b', accent1: '#fbbf24', accent2: '#f59e0b', accent3: '#d97706',
                    illusStart: '#1e293b', illusEnd: '#0f172a'
                }
            ];

            const p = palettes[Math.floor(Math.random() * palettes.length)];

            const setColor = (key, value) => {
                const $input = $(`input[data-css-var="${key}"]`);
                if ($input.length) {
                    $input.val(value).trigger('input');
                } else {
                    applyCssVar(key, value);
                }
            };

            setColor('--rami-card-bg', p.bg);
            setColor('--rami-card-border-color', p.border);
            setColor('--rami-group-1-color', p.accent1);
            setColor('--rami-group-2-color', p.accent2);
            setColor('--rami-group-3-color', p.accent3);
            setColor('--rami-illustration-bg-start', p.illusStart);
            setColor('--rami-illustration-bg-end', p.illusEnd);

            // 3. Random Geometry (Sliders)
            const setSlider = (key, min, max) => {
                const $input = $(`input[data-css-var="${key}"]`);
                if ($input.length) {
                    const val = Math.floor(Math.random() * (max - min + 1)) + min;
                    $input.val(val).trigger('input');
                }
            };

            setSlider('--rami-card-border-width', 0, 4);
            setSlider('--rami-card-radius', 0, 24);
            setSlider('--rami-illustration-border-width', 0, 4);

            // Randomize decoration sizes slightly for variety
            setSlider('--rami-index-pronoun-size', 24, 30);
            setSlider('--rami-verb-size', 20, 24);

            showMessage('success', 'Thème magique généré ! ✨');
            setTimeout(() => btn.removeClass('btn-loading'), 500);
            markUnsaved();
        });

        $('#theme-save').on('click', function () {
            if (isSavingTheme) return;
            // Toujours autoriser l'enregistrement, même si aucune modification détectée
            // Utile pour les nouvelles versions (V5+) où certains champs sont appliqués dynamiquement

            isSavingTheme = true;
            const btn = $(this);
            btn.addClass('btn-loading');
            $resetBtn.prop('disabled', true);
            $magicBtn.prop('disabled', true);
            setSaveUi('saving');

            // Base: toutes les infos connues (settings courants puis defaults)
            let settings = {};
            try {
                const current = JSON.parse($('#theme-settings').attr('data-settings') || '{}');
                const defaults = JSON.parse($('#theme-defaults').attr('data-defaults') || '{}');
                settings = Object.assign({}, defaults, current);
            } catch (e) {
                settings = {};
            }

            // Écrase avec les valeurs des champs présents dans le formulaire
            $('[data-css-var]').each(function () {
                const key = $(this).data('css-var');
                let value = $(this).val();
                const unit = $(this).data('unit');
                if (unit) value += unit;
                settings[key] = value;
            });

            // Enfin, fusionne les variables CSS modifiées via les outils (liveCssVars)
            for (const k in liveCssVars) {
                if (Object.prototype.hasOwnProperty.call(liveCssVars, k)) {
                    settings[k] = liveCssVars[k];
                }
            }

            $.ajax({
                url: '/admin/theme',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                data: { settings, uiVersion: $('#theme-ui-version').val() || 1 },
                success: (data) => {
                    hasUnsavedChanges = false;
                    setSaveUi('saved');
                    showMessage('success', (data && data.message) ? data.message : 'Thème enregistré avec succès');
                    setTimeout(() => {
                        if (!hasUnsavedChanges) setSaveUi('idle');
                    }, 2200);
                },
                error: (xhr) => {
                    const msg = (xhr && xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.error))
                        ? (xhr.responseJSON.message || xhr.responseJSON.error)
                        : 'Erreur lors de la sauvegarde';
                    showMessage('error', msg);
                    setSaveUi('unsaved');
                },
                complete: () => {
                    isSavingTheme = false;
                    btn.removeClass('btn-loading');
                    $resetBtn.prop('disabled', false);
                    $magicBtn.prop('disabled', false);
                }
            });
        });

        $('#theme-reset').on('click', function () {
            if (isSavingTheme) return;
            if (confirm('Réinitialiser tous les paramètres ?')) {
                const btn = $(this);
                btn.addClass('btn-loading');
                $saveBtn.prop('disabled', true);
                $magicBtn.prop('disabled', true);
                if ($status.length) $status.text('Réinitialisation…').removeClass('is-unsaved is-saved');

                $.ajax({
                    url: '/admin/theme/reset',
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    data: { uiVersion: $('#theme-ui-version').val() || 1 },
                    success: () => {
                        location.reload();
                    },
                    error: (xhr) => {
                        btn.removeClass('btn-loading');
                        $magicBtn.prop('disabled', false);
                        setSaveUi(hasUnsavedChanges ? 'unsaved' : 'idle');

                        const msg = (xhr && xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.error))
                            ? (xhr.responseJSON.message || xhr.responseJSON.error)
                            : 'Erreur lors de la réinitialisation';
                        showMessage('error', msg);
                    },
                });
            }
        });

        function showMessage(type, text) {
            if (typeof window.showNotification === 'function') {
                window.showNotification(text, type === 'success' ? 'success' : 'error');
                return;
            }

            const $msg = $('#theme-message');
            $msg.attr('class', `alert alert-${type}`).html(
                `<i class="ph ph-${type === 'success' ? 'check-circle' : 'warning-circle'}"></i> ${text}`
            ).fadeIn();
            setTimeout(() => $msg.fadeOut(), 3000);
        }

        $(window).on('beforeunload', function () {
            if (!hasUnsavedChanges) return;
            return 'Des modifications ne sont pas enregistrées.';
        });

        $(document).on('keydown', function (e) {
            if (e.key === 'Escape' && $themeCustomizer.hasClass('is-preview-fullscreen')) {
                e.preventDefault();
                setPreviewFullscreen(false);
                return;
            }

            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
                if (!$('#theme-save').length) return;
                e.preventDefault();
                $('#theme-save').trigger('click');
            }

            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                const $search = $('#customizer-search');
                if (!$search.length) return;
                e.preventDefault();
                $search.trigger('focus').trigger('select');
            }
        });
    }

    // ========================================
    // API HELPERS
    // ========================================
    window.FrenchVerbs = {
        /**
         * Récupérer tous les verbes via API
         */
        getVerbs: function (callback) {
            $.ajax({
                url: '/api/verbs',
                method: 'GET',
                success: function (data) {
                    if (callback) callback(null, data);
                },
                error: function (xhr) {
                    if (callback) callback(xhr.responseJSON, null);
                }
            });
        },

        /**
         * Récupérer un verbe spécifique
         */
        getVerb: function (id, callback) {
            $.ajax({
                url: '/api/verbs/' + id,
                method: 'GET',
                success: function (data) {
                    if (callback) callback(null, data);
                },
                error: function (xhr) {
                    if (callback) callback(xhr.responseJSON, null);
                }
            });
        },

        /**
         * Générer les conjugaisons d'un verbe du 1er groupe
         */
        conjugate1erGroupe: function (infinitive) {
            if (!infinitive.endsWith('er')) return null;

            const radical = infinitive.slice(0, -2);

            // Cas spéciaux pour les verbes en -ger (manger)
            const radicalNous = infinitive.endsWith('ger')
                ? infinitive.slice(0, -2) + 'e'
                : radical;

            return {
                je: radical + 'e',
                tu: radical + 'es',
                'il/elle/on': radical + 'e',
                nous: radicalNous + 'ons',
                vous: radical + 'ez',
                'ils/elles': radical + 'ent'
            };
        },

        /**
         * Générer les conjugaisons d'un verbe du 2ème groupe
         */
        conjugate2emeGroupe: function (infinitive) {
            if (!infinitive.endsWith('ir')) return null;

            const radical = infinitive.slice(0, -2);

            return {
                je: radical + 'is',
                tu: radical + 'is',
                'il/elle/on': radical + 'it',
                nous: radical + 'issons',
                vous: radical + 'issez',
                'ils/elles': radical + 'issent'
            };
        }
    };

    // ========================================
    // KEYBOARD NAVIGATION
    // ========================================
    $(document).on('keydown', function (e) {
        // Échap pour fermer les modales, retourner, etc.
        if (e.key === 'Escape') {
            $('.modal').removeClass('active');
        }

        // Impression rapide avec Ctrl+P sur page de carte
        if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
            const printBtn = $('.btn[onclick*="print"]');
            if (printBtn.length) {
                e.preventDefault();
                window.print();
            }
        }
    });

    // ========================================
    // ANIMATIONS ON SCROLL
    // ========================================
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    $(entry.target).addClass('animate-fade-in');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        $('.verb-card, .form-card').each(function () {
            observer.observe(this);
        });
    }

    // ========================================
    // ACCESSIBILITY ENHANCEMENTS
    // ========================================
    // Ajouter des attributs ARIA dynamiquement
    $('.verb-card').attr({
        'role': 'article',
        'tabindex': '0'
    }).on('keydown', function (e) {
        // Permettre la navigation au clavier
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            $(this).click();
        }
    });

    $('.filter-tab').attr('role', 'tab');
    $('#filter-tabs').attr('role', 'tablist');

    // ========================================
    // NOTIFICATION SYSTEM
    // ========================================
    window.showNotification = function (message, type = 'success') {
        const normalizedType = type === 'error' ? 'error' : 'success';
        const $container = $('#notification-container').length
            ? $('#notification-container')
            : $('<div id="notification-container" class="notification-container" aria-live="polite" aria-atomic="true"></div>').appendTo('body');

        const $notification = $(`
            <div class="notification notification-${normalizedType}" role="status" tabindex="0">
                <i class="ph ${normalizedType === 'success' ? 'ph-check-circle' : 'ph-warning-circle'}"></i>
                <span>${message}</span>
            </div>
        `);

        let hideTimeoutId = null;
        let removeTimeoutId = null;

        const clearTimers = () => {
            if (hideTimeoutId) clearTimeout(hideTimeoutId);
            if (removeTimeoutId) clearTimeout(removeTimeoutId);
            hideTimeoutId = null;
            removeTimeoutId = null;
        };

        const removeNow = () => {
            clearTimers();
            $notification.removeClass('show');
            setTimeout(() => $notification.remove(), 200);
        };

        const scheduleAutoDismiss = () => {
            clearTimers();
            hideTimeoutId = setTimeout(() => {
                $notification.removeClass('show');
                removeTimeoutId = setTimeout(() => $notification.remove(), 200);
            }, 3000);
        };

        $notification.on('click', removeNow);
        $notification.on('keydown', function (e) {
            if (e.key === 'Escape' || e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                removeNow();
            }
        });
        $notification.on('mouseenter focusin', clearTimers);
        $notification.on('mouseleave focusout', scheduleAutoDismiss);

        $container.append($notification);

        setTimeout(() => {
            $notification.addClass('show');
        }, 10);

        scheduleAutoDismiss();
    };

    const toastifyAlerts = () => {
        if (typeof window.showNotification !== 'function') return;

        $('.alert').each(function () {
            const $alert = $(this);
            if ($alert.data('toastified')) return;
            if (!$alert.find('.alert-close').length) return;

            const type = $alert.hasClass('alert-danger') || $alert.hasClass('alert-error')
                ? 'error'
                : ($alert.hasClass('alert-success') ? 'success' : null);

            if (!type) return;

            const $clone = $alert.clone();
            $clone.find('.alert-close').remove();
            const message = String($clone.text() || '').replace(/\s+/g, ' ').trim();
            if (!message) return;

            $alert.data('toastified', true);
            window.showNotification(message, type);
            $alert.remove();
        });
    };

    toastifyAlerts();

    $(document).on('click', 'input.color-hex[readonly]', function () {
        const input = this;
        const text = String($(input).val() || '').trim();
        if (!text) return;

        input.focus();
        input.select();

        const onSuccess = () => {
            if (typeof window.showNotification === 'function') {
                window.showNotification('Copié dans le presse-papiers', 'success');
            }
        };

        const onError = () => {
            if (typeof window.showNotification === 'function') {
                window.showNotification('Impossible de copier automatiquement', 'error');
            }
        };

        if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
            navigator.clipboard.writeText(text).then(onSuccess).catch(onError);
            return;
        }

        try {
            document.execCommand('copy');
            onSuccess();
        } catch (e) {
            onError();
        }
    });

    $(document).on('submit', 'form', function () {
        const $form = $(this);
        if ($form.data('isSubmitting')) return false;
        $form.data('isSubmitting', true);

        const $submitButtons = $form.find('button[type="submit"], input[type="submit"]');
        $submitButtons.each(function () {
            const $btn = $(this);
            $btn.attr('aria-disabled', 'true');
            if ($btn.is('button')) {
                $btn.prop('disabled', true);
                if ($btn.hasClass('btn')) $btn.addClass('btn-loading');
            } else {
                $btn.prop('disabled', true);
            }
        });

        return true;
    });

    // ========================================
    // AUDIO PRONUNCIATION (TTS)
    // ========================================
    const synth = window.speechSynthesis;

    $(document).on('click', '.btn-audio-player', function (e) {
        e.preventDefault();
        e.stopPropagation();

        const $btn = $(this);
        const text = $btn.data('text');

        if (!text) return;

        if (synth.speaking) {
            synth.cancel();
        }

        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'fr-FR';
        utterance.rate = 0.9; // Slightly slower feels more educational

        $btn.addClass('btn-audio-playing');

        utterance.onend = function () {
            $btn.removeClass('btn-audio-playing');
        };

        utterance.onerror = function () {
            $btn.removeClass('btn-audio-playing');
            // Quietly fail or show log
            console.warn('TTS Error');
        };

        synth.speak(utterance);
    });

    const shuffleArray = (arr) => {
        const copy = Array.isArray(arr) ? arr.slice() : [];
        for (let i = copy.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            const tmp = copy[i];
            copy[i] = copy[j];
            copy[j] = tmp;
        }
        return copy;
    };

    const getSuitSymbolForVerb = (verb) => {
        const infinitive = String(verb && verb.infinitive ? verb.infinitive : '').toLowerCase();
        if (infinitive === 'être' || infinitive === 'avoir') return '♥';
        const group = String(verb && verb.group ? verb.group : '').trim();
        if (group === '1er') return '♠';
        if (group === '2ème') return '♦';
        if (group === '3ème') return '♣';
        return '';
    };

    const fetchVerbs = ({ group } = {}) => {
        const query = group ? `?group=${encodeURIComponent(group)}` : '';
        return $.ajax({
            url: `/api/verbs${query}`,
            method: 'GET',
        }).then((verbs) => (Array.isArray(verbs) ? verbs : []));
    };

    const $memoryRoot = $('#memory-root');
    if ($memoryRoot.length) {
        const $grid = $('#memory-grid');
        const $start = $('#memory-start');
        const $reset = $('#memory-reset');
        const $pairs = $('#memory-pairs');
        const $group = $('#memory-group');
        const backPattern = String($memoryRoot.data('back-pattern') || '').trim() || 'plain';

        let state = {
            deck: [],
            first: null,
            lock: false,
            matched: 0,
            totalPairs: 0,
        };

        const clearGrid = () => {
            $grid.empty();
            state = { deck: [], first: null, lock: false, matched: 0, totalPairs: 0 };
        };

        const renderGrid = () => {
            $grid.empty();
            state.deck.forEach((card, idx) => {
                const $card = $(`
                    <button type="button" class="memory-card" data-index="${idx}" data-state="down" aria-label="Carte de memory">
                        <div class="memory-card-inner">
                            <div class="memory-card-face memory-card-face-back rami-card" data-group="1er" data-pattern="${backPattern}">
                                <div class="memory-card-back-label">FrenchVerbs</div>
                            </div>
                            <div class="memory-card-face memory-card-face-front rami-card" data-group="${card.group}" data-pattern="plain">
                                <div class="memory-card-front">
                                    <div class="memory-card-front-top">
                                        <span class="memory-card-suit" aria-hidden="true">${card.suit}</span>
                                        <span class="memory-card-tag">${card.kind === 'inf' ? 'INF' : 'PRÉSENT'}</span>
                                    </div>
                                    <div class="memory-card-front-text">${card.text}</div>
                                </div>
                            </div>
                        </div>
                    </button>
                `);
                $grid.append($card);
            });
        };

        const setCardState = (index, nextState) => {
            const $card = $grid.find(`.memory-card[data-index="${index}"]`);
            if (!$card.length) return;
            $card.attr('data-state', nextState);
        };

        const markMatched = (aIndex, bIndex) => {
            const $a = $grid.find(`.memory-card[data-index="${aIndex}"]`);
            const $b = $grid.find(`.memory-card[data-index="${bIndex}"]`);
            $a.attr('data-state', 'matched').prop('disabled', true).attr('aria-disabled', 'true');
            $b.attr('data-state', 'matched').prop('disabled', true).attr('aria-disabled', 'true');
        };

        const buildDeck = (verbs, pairsCount) => {
            const usable = (Array.isArray(verbs) ? verbs : []).filter((v) => Array.isArray(v.present) && v.present.length);
            const shuffled = shuffleArray(usable);
            const picked = shuffled.slice(0, pairsCount);

            const cards = [];
            picked.forEach((verb, i) => {
                const suit = getSuitSymbolForVerb(verb);
                const presentLine = shuffleArray(verb.present)[0];
                const pronoun = String(presentLine && presentLine.pronoun ? presentLine.pronoun : '').trim();
                const conjugation = String(presentLine && presentLine.conjugation ? presentLine.conjugation : '').trim();
                const pairId = `${String(verb.id)}:${i}`;
                const group = String(verb.group || '1er');

                cards.push({
                    pairId,
                    kind: 'inf',
                    group,
                    suit,
                    text: String(verb.infinitive || '').toUpperCase(),
                });
                cards.push({
                    pairId,
                    kind: 'conj',
                    group,
                    suit,
                    text: `${String(pronoun || '').toUpperCase()} ${conjugation}`,
                });
            });

            return shuffleArray(cards).map((card, idx) => ({ ...card, index: idx }));
        };

        const startGame = () => {
            const pairsCount = Math.max(2, Math.min(12, parseInt(String($pairs.val() || '6'), 10) || 6));
            const group = String($group.val() || '').trim();

            clearGrid();

            $start.addClass('btn-loading').prop('disabled', true).attr('aria-disabled', 'true');

            fetchVerbs({ group }).then((verbs) => {
                const usableCount = (Array.isArray(verbs) ? verbs : []).filter((v) => Array.isArray(v.present) && v.present.length).length;
                if (usableCount < pairsCount) {
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('Pas assez de verbes pour ce nombre de paires', 'error');
                    }
                    $start.removeClass('btn-loading').prop('disabled', false).removeAttr('aria-disabled');
                    return;
                }

                state.deck = buildDeck(verbs, pairsCount);
                state.totalPairs = pairsCount;
                renderGrid();
                $reset.show();
            }).always(() => {
                $start.removeClass('btn-loading').prop('disabled', false).removeAttr('aria-disabled');
            });
        };

        $start.on('click', startGame);
        $reset.on('click', function () {
            clearGrid();
            $reset.hide();
        });

        $grid.on('click', '.memory-card', function () {
            if (state.lock) return;
            const $btn = $(this);
            const index = parseInt(String($btn.data('index') || ''), 10);
            if (!Number.isFinite(index)) return;
            if ($btn.attr('data-state') === 'matched') return;
            if ($btn.attr('data-state') === 'up') return;

            setCardState(index, 'up');
            const card = state.deck[index];
            if (!card) return;

            if (!state.first) {
                state.first = { index, pairId: card.pairId, kind: card.kind };
                return;
            }

            const second = { index, pairId: card.pairId, kind: card.kind };
            const first = state.first;
            state.first = null;

            if (first.index === second.index) return;

            const isMatch = first.pairId === second.pairId && first.kind !== second.kind;
            if (isMatch) {
                markMatched(first.index, second.index);
                state.matched += 1;
                if (state.matched >= state.totalPairs) {
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('Bravo ! Toutes les paires sont trouvées', 'success');
                    }
                }
                return;
            }

            state.lock = true;
            setTimeout(() => {
                setCardState(first.index, 'down');
                setCardState(second.index, 'down');
                state.lock = false;
            }, 850);
        });
    }

    const $dealRoot = $('#deal-root');
    if ($dealRoot.length) {
        const $stack = $('#deal-stack');
        const $hand = $('#deal-hand-area');
        const $shuffle = $('#deal-shuffle');
        const $deal = $('#deal-deal');
        const $reset = $('#deal-reset');
        const $count = $('#deal-count');
        const $handSize = $('#deal-hand');
        const backPattern = String($dealRoot.data('back-pattern') || '').trim() || 'plain';

        let deck = [];

        const renderStack = () => {
            $stack.empty();
            const visible = Math.min(deck.length, 28);
            for (let i = 0; i < visible; i++) {
                const depth = visible - i - 1;
                const offset = depth * 0.7;
                const rotate = (depth % 5 - 2) * 0.4;
                const $card = $(`
                    <div class="deal-stack-card" style="transform: translate(${offset}px, ${offset}px) rotate(${rotate}deg); z-index: ${i};">
                        <div class="deal-card-face rami-card" data-group="1er" data-pattern="${backPattern}">
                            <div class="deal-card-back-label">FrenchVerbs</div>
                        </div>
                    </div>
                `);
                $stack.append($card);
            }
            $stack.attr('data-count', String(deck.length));
        };

        const renderHandCard = (card) => {
            const $card = $(`
                <button type="button" class="deal-hand-card" data-state="down" aria-label="Carte distribuée">
                    <div class="deal-hand-inner">
                        <div class="deal-hand-face deal-hand-face-back rami-card" data-group="1er" data-pattern="${backPattern}">
                            <div class="deal-card-back-label">FrenchVerbs</div>
                        </div>
                        <div class="deal-hand-face deal-hand-face-front rami-card" data-group="${card.group}" data-pattern="plain">
                            <div class="deal-front">
                                <div class="deal-front-top">
                                    <span class="deal-front-suit" aria-hidden="true">${card.suit}</span>
                                    <span class="deal-front-pronoun">${card.pronoun}</span>
                                </div>
                                <div class="deal-front-conjugation">${card.conjugation}</div>
                                <div class="deal-front-infinitive">${String(card.infinitive || '').toUpperCase()}</div>
                            </div>
                        </div>
                    </div>
                </button>
            `);
            $hand.append($card);
        };

        const buildDeck = (verbs, count) => {
            const usable = (Array.isArray(verbs) ? verbs : []).filter((v) => Array.isArray(v.present) && v.present.length);
            const picked = shuffleArray(usable).slice(0, count);

            return picked.map((verb) => {
                const suit = getSuitSymbolForVerb(verb);
                const group = String(verb.group || '1er');
                const line = shuffleArray(verb.present)[0] || {};
                const pronoun = String(line.pronoun || '').trim();
                const conjugation = String(line.conjugation || '').trim();
                return {
                    id: verb.id,
                    infinitive: String(verb.infinitive || ''),
                    group,
                    suit,
                    pronoun: pronoun ? pronoun.toUpperCase() : '',
                    conjugation,
                };
            });
        };

        const loadDeckIfNeeded = () => {
            const targetCount = Math.max(6, Math.min(52, parseInt(String($count.val() || '18'), 10) || 18));
            if (deck.length === targetCount) return $.Deferred().resolve(deck);
            return fetchVerbs().then((verbs) => {
                const usableCount = (Array.isArray(verbs) ? verbs : []).filter((v) => Array.isArray(v.present) && v.present.length).length;
                if (usableCount < targetCount) {
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('Pas assez de verbes pour créer le tas', 'error');
                    }
                    deck = [];
                    renderStack();
                    return [];
                }
                deck = buildDeck(verbs, targetCount);
                renderStack();
                return deck;
            });
        };

        const animateFly = ($from, $to, onDone) => {
            const fromRect = $from[0].getBoundingClientRect();
            const toRect = $to[0].getBoundingClientRect();
            const startX = fromRect.left + fromRect.width / 2;
            const startY = fromRect.top + fromRect.height / 2;
            const endX = toRect.left + toRect.width / 2;
            const endY = toRect.top + toRect.height / 2;

            const $fly = $(`
                <div class="deal-fly" style="left:${startX}px; top:${startY}px;">
                    <div class="deal-fly-face rami-card" data-group="1er" data-pattern="${backPattern}">
                        <div class="deal-card-back-label">FrenchVerbs</div>
                    </div>
                </div>
            `).appendTo('body');

            const dx = endX - startX;
            const dy = endY - startY;

            requestAnimationFrame(() => {
                $fly.addClass('is-flying');
                $fly.css('transform', `translate(${dx}px, ${dy}px) rotate(${(Math.random() * 12 - 6).toFixed(2)}deg)`);
            });

            const done = () => {
                $fly.off('transitionend', done);
                $fly.remove();
                if (typeof onDone === 'function') onDone();
            };

            $fly.on('transitionend', done);
            setTimeout(done, 900);
        };

        $shuffle.on('click', function () {
            $shuffle.addClass('btn-loading').prop('disabled', true).attr('aria-disabled', 'true');
            loadDeckIfNeeded().then(() => {
                deck = shuffleArray(deck);
                $stack.addClass('is-shuffling');
                renderStack();
                setTimeout(() => $stack.removeClass('is-shuffling'), 650);
            }).always(() => {
                $shuffle.removeClass('btn-loading').prop('disabled', false).removeAttr('aria-disabled');
            });
        });

        $deal.on('click', function () {
            const n = Math.max(1, Math.min(12, parseInt(String($handSize.val() || '7'), 10) || 7));
            $deal.addClass('btn-loading').prop('disabled', true).attr('aria-disabled', 'true');

            loadDeckIfNeeded().then(() => {
                if (!deck.length) return;

                const from = $stack.find('.deal-stack-card').first();
                if (!from.length) return;

                for (let i = 0; i < n; i++) {
                    const card = deck.shift();
                    if (!card) break;

                    setTimeout(() => {
                        const $from = $stack.find('.deal-stack-card').first();
                        if (!$from.length) return;
                        animateFly($from, $hand, () => {
                            renderHandCard(card);
                            renderStack();
                        });
                    }, i * 140);
                }
            }).always(() => {
                setTimeout(() => {
                    $deal.removeClass('btn-loading').prop('disabled', false).removeAttr('aria-disabled');
                }, 600);
            });
        });

        $reset.on('click', function () {
            deck = [];
            $hand.empty();
            $stack.empty();
            $stack.removeAttr('data-count');
        });

        $hand.on('click', '.deal-hand-card', function () {
            const $card = $(this);
            const next = $card.attr('data-state') === 'up' ? 'down' : 'up';
            $card.attr('data-state', next);
        });
    }

    console.log('🇫🇷 FrenchVerbs - Application chargée avec succès');
});
