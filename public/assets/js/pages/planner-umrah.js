document.addEventListener('DOMContentLoaded', function () {
    var cfgEl = document.getElementById('planner-page-config');
    if (!cfgEl) {
        return;
    }
    var cfg = {};
    try {
        cfg = JSON.parse(cfgEl.textContent || '{}');
    } catch (e) {
        return;
    }

    var calculateUrl = cfg.calculateUrl;
    var hajjPlannerUrl = cfg.hajjPlannerUrl;
    var yearsLabel = cfg.yearsLabel;
    var msgProcessing = cfg.msgProcessing;
    var msgCalculate = cfg.msgCalculate;
    var msgErrorGeneric = cfg.msgErrorGeneric;
    var msgErrorNetwork = cfg.msgErrorNetwork;
    var chartLegendLines = cfg.chartLegendLines;
    var lblContribution = cfg.lblContribution;
    var lblGain = cfg.lblGain;
    var lblRs = cfg.lblRs;

    const form = document.getElementById('umrah-planner-form');
    const ageSlider = document.getElementById('age-slider');
    const ageDisplay = document.getElementById('age-display');
    const planTermSelect = document.getElementById('plan-term');
    const planSelect = document.getElementById('plan-select');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const btnLoader = document.getElementById('btn-loader');
    
    const formWrapper = document.getElementById('planner-form-wrapper');
    const resultsWrapper = document.getElementById('planner-results');
    const replanBtn = document.getElementById('replan-btn');

    const termDisplay = document.getElementById('term-display');

    let chartLine = null;
    let chartDonut9 = null;
    let chartDonut13 = null;

    planSelect.addEventListener('change', function () {
        if (this.value === 'hajj') {
            window.location.href = hajjPlannerUrl;
        }
    });

    function syncTermDisplay() {
        termDisplay.textContent = planTermSelect.value + ' ' + yearsLabel;
    }

    planTermSelect.addEventListener('change', syncTermDisplay);

    ageSlider.addEventListener('input', function() {
        const age = parseInt(this.value, 10);
        ageDisplay.textContent = age + ' ' + yearsLabel;

        if (age === 55) {
            planTermSelect.value = '5';
            planTermSelect.disabled = true;
            planTermSelect.classList.add('hp-range--dimmed');
            termDisplay.textContent = '5 ' + yearsLabel;
        } else {
            planTermSelect.disabled = false;
            planTermSelect.classList.remove('hp-range--dimmed');
            syncTermDisplay();
        }
    });

    syncTermDisplay();
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // UI Loading State
        submitBtn.disabled = true;
        btnText.textContent = msgProcessing;
        btnLoader.classList.remove('hidden');

        try {
            const formData = new FormData(form);
            const response = await fetch(calculateUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            let data;
            try {
                data = await response.json();
            } catch (parseErr) {
                alert(msgErrorNetwork);
                return;
            }

            if (data.success) {
                showResults(data);
            } else {
                let msg = data.message || '';
                if (data.errors && typeof data.errors === 'object') {
                    const parts = Object.values(data.errors).flat().filter(Boolean);
                    if (parts.length) {
                        msg = [msg, ...parts].filter(Boolean).join('\n');
                    }
                }
                alert(msg || msgErrorGeneric);
            }
        } catch (error) {
            console.error('Error:', error);
            alert(msgErrorNetwork);
        } finally {
            submitBtn.disabled = false;
            btnText.textContent = msgCalculate;
            btnLoader.classList.add('hidden');
        }
    });

    replanBtn.addEventListener('click', function() {
        resultsWrapper.classList.add('hidden');
        formWrapper.classList.remove('hidden');
        window.scrollTo({ top: formWrapper.offsetTop - 100, behavior: 'smooth' });
    });

    function showResults(data) {
        const s = data.summary;
        const t = data.totals;
        document.getElementById('res-age').textContent = s.age;
        document.getElementById('res-term').textContent = s.term;
        document.getElementById('res-annual').textContent = Number(s.annual_contribution).toLocaleString();
        document.getElementById('res-benefit').textContent = Number(s.takaful_benefit).toLocaleString();
        document.getElementById('res-total').textContent = Number(t.contribution).toLocaleString();
        document.getElementById('res-return-9').textContent = Number(t.return_9).toLocaleString();
        document.getElementById('res-return-13').textContent = Number(t.return_13).toLocaleString();

        document.getElementById('res-hero-term').textContent = s.term;
        const hero = Math.round((Number(t.return_9) + Number(t.return_13)) / 2);
        document.getElementById('res-hero-amount').textContent = hero.toLocaleString();

        formWrapper.classList.add('hidden');
        resultsWrapper.classList.remove('hidden');
        window.scrollTo({ top: resultsWrapper.offsetTop - 80, behavior: 'smooth' });

        renderCharts(data.charts, data.totals);
    }

    function decorateLineLabels(labels) {
        const y0 = new Date().getFullYear();
        return (labels || []).map(function (lbl) {
            const m = /^Year\s+(\d+)/i.exec(String(lbl));
            if (m) {
                return String(y0 + parseInt(m[1], 10));
            }
            return lbl;
        });
    }

    function fillDoughnutLegend(elementId, contribution, totalReturn) {
        const el = document.getElementById(elementId);
        if (!el || totalReturn <= 0) {
            return;
        }
        const gain = Math.max(0, totalReturn - contribution);
        const pctC = Math.round((contribution / totalReturn) * 100);
        const pctG = Math.max(0, 100 - pctC);
        const fmt = function (n) {
            return Math.round(Number(n) || 0).toLocaleString();
        };
        el.innerHTML =
            '<div class="hp-res__lg-row"><span class="hp-res__lg-swatch hp-res__lg-swatch--contrib"></span><span>' +
            lblContribution + ' ' + pctC + '% ' + lblRs + ' ' + fmt(contribution) +
            '</span></div><div class="hp-res__lg-row"><span class="hp-res__lg-swatch hp-res__lg-swatch--gain"></span><span>' +
            lblGain + ' ' + pctG + '% ' + lblRs + ' ' + fmt(gain) +
            '</span></div>';
    }

    function renderCharts(chartData, totals) {
        if (chartLine) {
            chartLine.destroy();
            chartLine = null;
        }
        if (chartDonut9) {
            chartDonut9.destroy();
            chartDonut9 = null;
        }
        if (chartDonut13) {
            chartDonut13.destroy();
            chartDonut13 = null;
        }

        const lineColors = ['#9ca3af', '#b08d57', '#2563eb'];
        const lineData = JSON.parse(JSON.stringify(chartData.line_chart));
        lineData.labels = decorateLineLabels(lineData.labels);
        (lineData.datasets || []).forEach(function (ds, i) {
            ds.label = chartLegendLines[i] || ds.label;
            ds.borderColor = lineColors[i] || '#666666';
            ds.backgroundColor = 'transparent';
            ds.fill = false;
            ds.tension = 0.25;
            ds.borderWidth = 2;
            ds.pointRadius = 3;
            ds.pointHoverRadius = 5;
            ds.pointBackgroundColor = lineColors[i];
            ds.pointBorderColor = lineColors[i];
        });

        chartLine = new Chart(document.getElementById('umrahLineChart'), {
            type: 'line',
            data: lineData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: {
                        position: 'bottom',
                        align: 'center',
                        labels: {
                            font: { family: "'Raleway', sans-serif", size: 11, weight: '500' },
                            color: '#333333',
                            padding: 18,
                            boxWidth: 10,
                            boxHeight: 10,
                            usePointStyle: true,
                            pointStyle: 'circle',
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: function (ctx) {
                                const v = ctx.parsed.y;
                                if (v === undefined || v === null) {
                                    return '';
                                }
                                return ' ' + ctx.dataset.label + ': PKR ' + Number(v).toLocaleString();
                            },
                        },
                    },
                },
                scales: {
                    x: {
                        ticks: { font: { family: "'Raleway', sans-serif", size: 11 }, color: '#555555' },
                        grid: { display: true, color: 'rgba(0,0,0,0.06)' },
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { family: "'Raleway', sans-serif", size: 11 },
                            color: '#555555',
                            maxTicksLimit: 8,
                            callback: function (value) {
                                return 'PKR ' + Number(value).toLocaleString();
                            },
                        },
                        grid: { color: 'rgba(0,0,0,0.07)' },
                    },
                },
            },
        });

        const donutGrey = '#d4d0c8';
        const donutGold = '#b08d57';
        const donutOpts = {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            const v = ctx.raw;
                            return ' ' + (ctx.label || '') + ': ' + lblRs + ' ' + Number(v).toLocaleString();
                        },
                    },
                },
            },
        };

        const d9 = JSON.parse(JSON.stringify(chartData.doughnut_9));
        if (d9.datasets && d9.datasets[0]) {
            d9.datasets[0].backgroundColor = [donutGrey, donutGold];
        }
        chartDonut9 = new Chart(document.getElementById('umrahDoughnut9'), {
            type: 'doughnut',
            data: d9,
            options: donutOpts,
        });

        const d13 = JSON.parse(JSON.stringify(chartData.doughnut_13));
        if (d13.datasets && d13.datasets[0]) {
            d13.datasets[0].backgroundColor = [donutGrey, donutGold];
        }
        chartDonut13 = new Chart(document.getElementById('umrahDoughnut13'), {
            type: 'doughnut',
            data: d13,
            options: donutOpts,
        });

        fillDoughnutLegend('doughnut9-legend', totals.contribution, totals.return_9);
        fillDoughnutLegend('doughnut13-legend', totals.contribution, totals.return_13);
    }
