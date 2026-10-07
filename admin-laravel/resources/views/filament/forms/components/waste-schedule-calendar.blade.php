@php
    $statePath = $getStatePath();
@endphp

<style>
    .wsc-shell {
        padding: 16px;
        overflow: hidden;
        border: 1px solid #dbe3ee;
        border-radius: 14px;
        background: #f8fafc;
    }

    .wsc-summary {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 16px;
    }

    .wsc-summary-title {
        margin: 0;
        color: #111827;
        font-size: 14px;
        line-height: 20px;
        font-weight: 700;
    }

    .wsc-summary-count {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 12px;
        line-height: 18px;
    }

    .wsc-summary-count strong {
        color: #0284c7;
    }

    .wsc-clear {
        padding: 7px 10px;
        border: 0;
        border-radius: 8px;
        background: #fff1f2;
        color: #dc2626;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .wsc-clear:hover {
        background: #ffe4e6;
    }

    .wsc-panel {
        overflow: hidden;
        border: 1px solid #dbe3ee;
        border-radius: 12px;
        background: #ffffff;
    }

    .wsc-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 12px;
        border-bottom: 1px solid #dbe3ee;
    }

    .wsc-month {
        margin: 0;
        color: #111827;
        font-size: 15px;
        line-height: 22px;
        font-weight: 800;
        text-transform: capitalize;
    }

    .wsc-nav {
        display: inline-flex;
        width: 34px;
        height: 34px;
        align-items: center;
        justify-content: center;
        padding: 0;
        border: 1px solid #dbe3ee;
        border-radius: 9px;
        background: #ffffff;
        color: #475569;
        cursor: pointer;
    }

    .wsc-nav:hover {
        border-color: #93c5fd;
        background: #eff6ff;
        color: #0369a1;
    }

    .wsc-nav svg {
        width: 20px;
        height: 20px;
    }

    .wsc-weekdays,
    .wsc-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
    }

    .wsc-weekdays {
        border-bottom: 1px solid #dbe3ee;
        background: #f1f5f9;
        color: #64748b;
        text-align: center;
        font-size: 12px;
        font-weight: 800;
    }

    .wsc-weekdays span {
        padding: 9px 4px;
    }

    .wsc-weekdays .wsc-sunday {
        color: #dc2626;
    }

    .wsc-grid {
        gap: 1px;
        background: #dbe3ee;
    }

    .wsc-day {
        position: relative;
        display: flex;
        min-width: 0;
        height: 50px;
        align-items: center;
        justify-content: center;
        padding: 0;
        border: 0;
        background: #ffffff;
        color: #334155;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: background-color .16s ease, color .16s ease, box-shadow .16s ease;
    }

    .wsc-day:hover {
        background: #e0f2fe;
        color: #0369a1;
    }

    .wsc-day--empty {
        background: #f8fafc;
        cursor: default;
    }

    .wsc-day--empty:hover {
        background: #f8fafc;
    }

    .wsc-day--selected,
    .wsc-day--selected:hover {
        background: #0284c7;
        color: #ffffff;
        box-shadow: inset 0 0 0 2px rgba(255, 255, 255, .25);
    }

    .wsc-day-number {
        display: inline-flex;
        width: 30px;
        height: 30px;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
    }

    .wsc-day-number--today {
        border: 2px solid #0284c7;
        color: #0369a1;
    }

    .wsc-legend {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-top: 12px;
        color: #64748b;
        font-size: 12px;
    }

    .wsc-legend-selected,
    .wsc-legend-today {
        display: inline-block;
        width: 13px;
        height: 13px;
        margin-left: 12px;
    }

    .wsc-legend-selected {
        margin-left: 0;
        border-radius: 3px;
        background: #0284c7;
    }

    .wsc-legend-today {
        border: 2px solid #0284c7;
        border-radius: 999px;
    }

    .dark .wsc-shell {
        border-color: rgba(255, 255, 255, .12);
        background: rgba(255, 255, 255, .035);
    }

    .dark .wsc-summary-title,
    .dark .wsc-month {
        color: #f8fafc;
    }

    .dark .wsc-summary-count,
    .dark .wsc-legend {
        color: #94a3b8;
    }

    .dark .wsc-panel,
    .dark .wsc-nav,
    .dark .wsc-day {
        border-color: rgba(255, 255, 255, .12);
        background: #111827;
        color: #e2e8f0;
    }

    .dark .wsc-toolbar {
        border-color: rgba(255, 255, 255, .12);
    }

    .dark .wsc-nav:hover,
    .dark .wsc-day:hover {
        background: rgba(2, 132, 199, .18);
        color: #7dd3fc;
    }

    .dark .wsc-weekdays {
        border-color: rgba(255, 255, 255, .12);
        background: rgba(255, 255, 255, .06);
        color: #94a3b8;
    }

    .dark .wsc-grid {
        background: rgba(255, 255, 255, .12);
    }

    .dark .wsc-day--empty,
    .dark .wsc-day--empty:hover {
        background: rgba(15, 23, 42, .68);
    }

    .dark .wsc-day--selected,
    .dark .wsc-day--selected:hover {
        background: #0284c7;
        color: #ffffff;
    }

    .dark .wsc-day-number--today {
        color: #7dd3fc;
    }

    @media (max-width: 640px) {
        .wsc-shell {
            padding: 10px;
        }

        .wsc-day {
            height: 44px;
        }
    }
</style>

<div
    wire:ignore
    x-data="{
        state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
        currentMonth: new Date(new Date().getFullYear(), new Date().getMonth(), 1),

        init() {
            this.setSelectedDates(this.selectedDates())
        },

        selectedDates() {
            if (! Array.isArray(this.state)) return []

            return [...new Set(this.state
                .map((item) => typeof item === 'string' ? item : item?.date)
                .map((item) => String(item || '').match(/^\d{4}-\d{2}-\d{2}/)?.[0] || '')
                .filter(Boolean)
            )].sort()
        },

        setSelectedDates(dates) {
            this.state = [...new Set(dates)].sort().map((date) => ({ date }))
        },

        formatDate(date) {
            return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`
        },

        isSelected(date) {
            return this.selectedDates().includes(date)
        },

        toggleDate(date) {
            const selected = this.selectedDates()
            const index = selected.indexOf(date)

            if (index === -1) {
                selected.push(date)
            } else {
                selected.splice(index, 1)
            }

            this.setSelectedDates(selected)
        },

        changeMonth(offset) {
            this.currentMonth = new Date(this.currentMonth.getFullYear(), this.currentMonth.getMonth() + offset, 1)
        },

        monthLabel() {
            return this.currentMonth.toLocaleDateString('vi-VN', { month: 'long', year: 'numeric' })
        },

        isToday(date) {
            const today = new Date()
            return date.getFullYear() === today.getFullYear() && date.getMonth() === today.getMonth() && date.getDate() === today.getDate()
        },

        calendarDays() {
            const year = this.currentMonth.getFullYear()
            const month = this.currentMonth.getMonth()
            const firstDay = new Date(year, month, 1)
            const leadingDays = (firstDay.getDay() + 6) % 7
            const daysInMonth = new Date(year, month + 1, 0).getDate()
            const cells = []

            for (let index = 0; index < leadingDays; index++) {
                cells.push({ key: `before-${index}`, current: false })
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(year, month, day)
                cells.push({ key: this.formatDate(date), current: true, date: this.formatDate(date), day, today: this.isToday(date) })
            }

            while (cells.length % 7 !== 0) {
                cells.push({ key: `after-${cells.length}`, current: false })
            }

            return cells
        },

        clearSelectedDates() {
            this.setSelectedDates([])
        }
    }"
    class="wsc-shell"
>
    <div class="wsc-summary">
        <div>
            <p class="wsc-summary-title">Chọn ngày thu gom trong tháng</p>
            <p class="wsc-summary-count">
                Đã chọn <strong x-text="selectedDates().length"></strong> ngày
            </p>
        </div>
        <button
            type="button"
            x-show="selectedDates().length > 0"
            x-on:click="clearSelectedDates()"
            class="wsc-clear"
        >
            Bỏ chọn tất cả
        </button>
    </div>

    <div class="wsc-panel">
        <div class="wsc-toolbar">
            <button type="button" x-on:click="changeMonth(-1)" class="wsc-nav" aria-label="Tháng trước">
                <x-filament::icon icon="heroicon-m-chevron-left" />
            </button>
            <p class="wsc-month" x-text="monthLabel()"></p>
            <button type="button" x-on:click="changeMonth(1)" class="wsc-nav" aria-label="Tháng sau">
                <x-filament::icon icon="heroicon-m-chevron-right" />
            </button>
        </div>

        <div class="wsc-weekdays">
            <span>T2</span><span>T3</span><span>T4</span><span>T5</span><span>T6</span><span>T7</span><span class="wsc-sunday">CN</span>
        </div>

        <div class="wsc-grid">
            <template x-for="day in calendarDays()" :key="day.key">
                <button
                    type="button"
                    x-on:click="day.current && toggleDate(day.date)"
                    x-bind:disabled="! day.current"
                    x-bind:title="day.current ? (isSelected(day.date) ? 'Bỏ chọn ngày ' + day.date : 'Chọn ngày ' + day.date) : ''"
                    x-bind:class="! day.current
                        ? 'wsc-day--empty'
                        : (isSelected(day.date)
                            ? 'wsc-day--selected'
                            : '')"
                    class="wsc-day"
                >
                    <span
                        x-show="day.current"
                        x-text="day.day"
                        class="wsc-day-number"
                        x-bind:class="day.today && ! isSelected(day.date) ? 'wsc-day-number--today' : ''"
                    ></span>
                </button>
            </template>
        </div>
    </div>

    <div class="wsc-legend">
        <span class="wsc-legend-selected"></span>
        Ngày đã chọn
        <span class="wsc-legend-today"></span>
        Hôm nay
    </div>
</div>
