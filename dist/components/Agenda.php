<style>
    /* 1. GENERAL CALENDAR HEADER & TOOLBAR */
    .fc-toolbar-title,
    .fc-col-header-cell-cushion,
    .fc-list-day-cushion,
    .fc-cell-shaded,
    .fc-popover-title {
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
        color: #000;
        text-decoration: none;
    }

    /* 2. THE "DEMO" CAPSULE LOOK (MONTH VIEW) */
    .fc-daygrid-event {
        border-radius: 4px !important;
        padding: 4px 6px !important;
        margin-top: 2px !important;
        border: none !important;
        height: auto !important;
        /* min-height: 2.8em !important; */
        display: block !important;
        transition: transform 0.1s ease, box-shadow 0.1s ease;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .fc-daygrid-event:hover {
        transform: scale(1.02);
        z-index: 10;
        filter: brightness(95%);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
    }

    /* 3. MULTI-LINE TEXT (TITLE + START + END) */
    .fc-event-main {
        display: block !important;
        white-space: normal !important;
    }

    .fc-event-title.fc-sticky,
    .fc-event-title {
        white-space: pre-wrap !important;
        /* Respects spaces/breaks */
        word-break: break-word !important;
        font-size: 0.9em !important;
        line-height: 1.3 !important;
        font-weight: 800;
        display: block !important;
    }

    /* 4. GRID & LAYOUT TWEAKS */
    .fc-daygrid-day-frame {
        /* min-height: 300px !important; */
        min-height: var(--day-height, 300px) !important;
        /* Taller cells for multi-line events */
    }

    .fc-timegrid-event {
        margin-bottom: 2px !important;
        min-height: 10px !important;
        /*hadi tzul bash yji kif kif 40*/
        /* Better visibility for 15-min breaks */
    }

    @media (min-width: 1024px) {
        .fc-toolbar-title {
            font-size: 1.5rem !important;
        }
    }

    /* 6. UTILITY */
    .fc-daygrid-more-link {
        font-weight: bold;
        color: #555 !important;
        font-size: 11px;
        background: #eee;
        padding: 2px 4px;
        border-radius: 3px;
    }

    .fc-header-toolbar {
        margin-bottom: 0.6em !important;
    }

    .fc-scroller {
        overflow: hidden !important;
    }

    .fc-scroller-liquid-absolute {
        overflow: auto !important;
    }

    /* Removes the glow/outline when clicking Prev, Next, Today, etc. */
    .fc .fc-button:focus,
    .fc .fc-button:active,
    .fc .fc-button-primary:focus,
    .fc .fc-button-primary:not(:disabled):active:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    /* Optional: If you want to remove the grey tint that stays after clicking */
    .fc .fc-button-primary:focus {
        background-color: #636e75 !important;
        /* Match your Flatly Navy color */
        border-color: #636e75 !important;
    }

    /* Optional: If you want the 'active' button to stay a flat color without the inner shadow */
    .fc .fc-button-active {
        background-color: #636e75 !important;
        /* Match your Flatly Navy color */
        border-color: #636e75 !important;
    }

    .fc-timegrid-event-harness {
        margin: 3px !important;
    }

    /* Ensure the header text doesn't wrap weirdly */
    .fc-col-header-cell-cushion {
        padding: 8px 4px !important;
        display: inline-block !important;
    }

    .fc-daygrid-day-number {
        color: #000;
        text-decoration: none;
    }

    .fc-dayGridMonth-view {
        border: 2px solid #0d6efd;
        border-radius: 10px;
        overflow: hidden;
    }

    .fc .fc-daygrid-day.fc-day-today {
        background-color: #ffe485 !important;
    }

    /*  */
</style>

<div class="card card-primary card-outline shadow-sm">
    <div class="card-body" style="padding:10px">
        <?php if ($userRole == 'A'): ?>
            <div class="form-group mb-2">
                <select id="agentSelect" class="form-control select2" style="width: 100%;">
                </select>
            </div>
        <?php endif; ?>
        <div id='calendar'></div>
    </div>
</div>