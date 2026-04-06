(function ($) {
    'use strict';

    var app = window.sdweddingdirectorySeatingChart || {};

    var MAX_SEATS = 30;
    var MAX_SIDE_SEATS = 15;
    var MAX_SQUARE_SIDE_SEATS = 7;
    var FLOOR_PADDING = 18;
    var SEAT_RING_OFFSET = 16;
    var TABLE_EDGE_PADDING = FLOOR_PADDING + SEAT_RING_OFFSET;
    var PX_PER_FOOT = 12;
    var PRINT_MAX_WIDTH_PX = 980;
    var PRINT_MAX_HEIGHT_PX = 660;

    var TABLE_DIMENSIONS = {
        round: { width: 120, height: 120 },
        rectangular: { width: 150, height: 92 },
        square: { width: 120, height: 120 }
    };

    var state = {
        tables: [],
        layout: {
            width_feet: 80,
            height_feet: 60,
            scale: 100
        }
    };

    var dragState = {
        active: false,
        tableId: '',
        offsetX: 0,
        offsetY: 0
    };

    function clamp(value, min, max) {
        return Math.max(min, Math.min(max, value));
    }

    function safeArray(value) {
        return Array.isArray(value) ? value : [];
    }

    function asInt(value, fallback) {
        var parsed = parseInt(value, 10);
        return isNaN(parsed) ? fallback : parsed;
    }

    function sanitizeShape(shape) {
        return (shape === 'rectangular' || shape === 'square') ? shape : 'round';
    }

    function normalizeLayout(layout) {
        var source = layout || {};

        return {
            width_feet: clamp(asInt(source.width_feet || source.widthFeet, 80), 10, 500),
            height_feet: clamp(asInt(source.height_feet || source.heightFeet, 60), 10, 500),
            scale: clamp(asInt(source.scale, 100), 1, 150)
        };
    }

    function layoutBaseSize() {
        return {
            width: state.layout.width_feet * PX_PER_FOOT,
            height: state.layout.height_feet * PX_PER_FOOT
        };
    }

    function distributedPositions(length, count) {
        var positions = [];

        if (count <= 0) {
            return positions;
        }

        if (count === 1) {
            return [length / 2];
        }

        var step = length / (count + 1);

        for (var i = 1; i <= count; i++) {
            positions.push(step * i);
        }

        return positions;
    }

    function deriveRectangularSides(targetSeats) {
        var target = clamp(asInt(targetSeats, 8), 4, MAX_SEATS);
        var best = {
            short_side_seats: 1,
            long_side_seats: 3,
            seats: 8,
            diff: Number.MAX_SAFE_INTEGER
        };

        for (var shortSide = 1; shortSide <= MAX_SIDE_SEATS; shortSide++) {
            for (var longSide = 1; longSide <= MAX_SIDE_SEATS; longSide++) {
                var total = 2 * (shortSide + longSide);

                if (total > MAX_SEATS) {
                    continue;
                }

                var diff = Math.abs(total - target);

                if (
                    diff < best.diff ||
                    (diff === best.diff && longSide > best.long_side_seats)
                ) {
                    best = {
                        short_side_seats: shortSide,
                        long_side_seats: longSide,
                        seats: total,
                        diff: diff
                    };
                }
            }
        }

        return {
            short_side_seats: best.short_side_seats,
            long_side_seats: best.long_side_seats,
            seats: best.seats
        };
    }

    function normalizeRectangularSides(shortSideRaw, longSideRaw, targetSeats) {
        var shortSide = asInt(shortSideRaw, 0);
        var longSide = asInt(longSideRaw, 0);

        if (shortSide < 1 || longSide < 1) {
            return deriveRectangularSides(targetSeats);
        }

        shortSide = clamp(shortSide, 1, MAX_SIDE_SEATS);
        longSide = clamp(longSide, 1, MAX_SIDE_SEATS);

        var total = 2 * (shortSide + longSide);

        while (total > MAX_SEATS) {
            if (longSide >= shortSide && longSide > 1) {
                longSide -= 1;
            } else if (shortSide > 1) {
                shortSide -= 1;
            } else {
                break;
            }

            total = 2 * (shortSide + longSide);
        }

        if (total < 4) {
            shortSide = 1;
            longSide = 1;
            total = 4;
        }

        return {
            short_side_seats: shortSide,
            long_side_seats: longSide,
            seats: total
        };
    }

    function normalizeSquareSide(sideRaw, targetSeats) {
        var sideSeats = asInt(sideRaw, 0);

        if (sideSeats < 1) {
            sideSeats = Math.max(1, Math.round(clamp(asInt(targetSeats, 8), 4, MAX_SEATS) / 4));
        }

        sideSeats = clamp(sideSeats, 1, MAX_SQUARE_SIDE_SEATS);

        while ((sideSeats * 4) > MAX_SEATS && sideSeats > 1) {
            sideSeats -= 1;
        }

        return sideSeats;
    }

    function updateAssignmentsLength(table) {
        table.assignments = safeArray(table.assignments).slice(0, table.seats);

        while (table.assignments.length < table.seats) {
            table.assignments.push('');
        }
    }

    function syncTableSeatConfig(table) {
        table.shape = sanitizeShape(table.shape);

        var requestedSeats = clamp(asInt(table.seats, 8), 1, MAX_SEATS);

        if (table.shape === 'rectangular') {
            var rect = normalizeRectangularSides(table.short_side_seats, table.long_side_seats, requestedSeats);
            table.short_side_seats = rect.short_side_seats;
            table.long_side_seats = rect.long_side_seats;
            table.seats = rect.seats;
            table.square_side_seats = normalizeSquareSide(table.square_side_seats, table.seats);
        } else if (table.shape === 'square') {
            var squareSide = normalizeSquareSide(table.square_side_seats, requestedSeats);
            table.square_side_seats = squareSide;
            table.short_side_seats = squareSide;
            table.long_side_seats = squareSide;
            table.seats = squareSide * 4;
        } else {
            table.seats = requestedSeats;

            if (asInt(table.short_side_seats, 0) < 1 || asInt(table.long_side_seats, 0) < 1) {
                var derivedRect = deriveRectangularSides(table.seats);
                table.short_side_seats = derivedRect.short_side_seats;
                table.long_side_seats = derivedRect.long_side_seats;
            } else {
                table.short_side_seats = clamp(asInt(table.short_side_seats, 1), 1, MAX_SIDE_SEATS);
                table.long_side_seats = clamp(asInt(table.long_side_seats, 1), 1, MAX_SIDE_SEATS);
            }

            table.square_side_seats = normalizeSquareSide(table.square_side_seats, table.seats);
        }

        updateAssignmentsLength(table);
        return table;
    }

    function normalizeTable(table, index) {
        var normalized = {
            id: (table.id || ('table_' + index + '_' + Date.now())).toString().replace(/[^a-zA-Z0-9_-]/g, ''),
            name: (table.name || '').toString(),
            shape: sanitizeShape(table.shape),
            seats: clamp(asInt(table.seats, 8), 1, MAX_SEATS),
            x: typeof table.x === 'number' ? table.x : parseFloat(table.x) || (TABLE_EDGE_PADDING + ((index % 4) * 36)),
            y: typeof table.y === 'number' ? table.y : parseFloat(table.y) || (TABLE_EDGE_PADDING + ((index % 3) * 36)),
            assignments: safeArray(table.assignments),
            short_side_seats: asInt(table.short_side_seats || table.shortSideSeats, 0),
            long_side_seats: asInt(table.long_side_seats || table.longSideSeats, 0),
            square_side_seats: asInt(table.square_side_seats || table.squareSideSeats || table.sideSeats, 0)
        };

        syncTableSeatConfig(normalized);

        normalized.x = Math.max(TABLE_EDGE_PADDING, normalized.x);
        normalized.y = Math.max(TABLE_EDGE_PADDING, normalized.y);

        return normalized;
    }

    function normalizeData(data) {
        var source = data || {};

        var tables = safeArray(source.tables).map(function (table, index) {
            return normalizeTable(table, index);
        });

        return {
            tables: tables,
            layout: normalizeLayout(source.layout)
        };
    }

    function guests() {
        return safeArray(app.guestPool);
    }

    function guestLabelById(id) {
        var selected = guests().find(function (g) {
            return String(g.id) === String(id);
        });

        return selected ? selected.label : (app.strings && app.strings.unassigned ? app.strings.unassigned : 'Unassigned');
    }

    function assignmentMap() {
        var map = {};

        state.tables.forEach(function (table) {
            safeArray(table.assignments).forEach(function (guestId, seatIndex) {
                if (!guestId) {
                    return;
                }

                map[String(guestId)] = {
                    tableId: table.id,
                    seatIndex: seatIndex
                };
            });
        });

        return map;
    }

    function dedupeAssignments() {
        var seen = {};

        state.tables.forEach(function (table) {
            safeArray(table.assignments).forEach(function (guestId, seatIndex) {
                var key = String(guestId || '');

                if (!key) {
                    return;
                }

                if (seen[key]) {
                    table.assignments[seatIndex] = '';
                    return;
                }

                seen[key] = true;
            });
        });
    }

    function clearDuplicateGuestAssignments(guestId, currentTableId, currentSeatIndex) {
        if (!guestId) {
            return;
        }

        state.tables.forEach(function (table) {
            safeArray(table.assignments).forEach(function (assignedId, seatIndex) {
                if (
                    String(assignedId) === String(guestId) &&
                    !(table.id === currentTableId && seatIndex === currentSeatIndex)
                ) {
                    table.assignments[seatIndex] = '';
                }
            });
        });
    }

    function tableDimensions(table) {
        return TABLE_DIMENSIONS[sanitizeShape(table.shape)] || TABLE_DIMENSIONS.round;
    }

    function roundSeatPositions(table, dims) {
        var positions = [];
        var centerX = dims.width / 2;
        var centerY = dims.height / 2;
        var radius = (Math.min(dims.width, dims.height) / 2) + SEAT_RING_OFFSET;

        for (var i = 0; i < table.seats; i++) {
            var angle = (-Math.PI / 2) + ((2 * Math.PI * i) / table.seats);
            positions.push({
                x: centerX + (radius * Math.cos(angle)),
                y: centerY + (radius * Math.sin(angle))
            });
        }

        return positions;
    }

    function fourSideSeatPositions(topCount, rightCount, bottomCount, leftCount, width, height) {
        var positions = [];

        var topXs = distributedPositions(width, topCount);
        var rightYs = distributedPositions(height, rightCount);
        var bottomXs = distributedPositions(width, bottomCount).slice().reverse();
        var leftYs = distributedPositions(height, leftCount).slice().reverse();

        topXs.forEach(function (x) {
            positions.push({ x: x, y: -SEAT_RING_OFFSET });
        });

        rightYs.forEach(function (y) {
            positions.push({ x: width + SEAT_RING_OFFSET, y: y });
        });

        bottomXs.forEach(function (x) {
            positions.push({ x: x, y: height + SEAT_RING_OFFSET });
        });

        leftYs.forEach(function (y) {
            positions.push({ x: -SEAT_RING_OFFSET, y: y });
        });

        return positions;
    }

    function rectangularSeatPositions(table, dims) {
        return fourSideSeatPositions(
            table.long_side_seats,
            table.short_side_seats,
            table.long_side_seats,
            table.short_side_seats,
            dims.width,
            dims.height
        );
    }

    function squareSeatPositions(table, dims) {
        return fourSideSeatPositions(
            table.square_side_seats,
            table.square_side_seats,
            table.square_side_seats,
            table.square_side_seats,
            dims.width,
            dims.height
        );
    }

    function seatPositions(table, dims) {
        if (table.shape === 'rectangular') {
            return rectangularSeatPositions(table, dims);
        }

        if (table.shape === 'square') {
            return squareSeatPositions(table, dims);
        }

        return roundSeatPositions(table, dims);
    }

    function tableShapeSummary(table) {
        if (table.shape === 'rectangular') {
            return 'rectangular, ' + table.short_side_seats + ' short / ' + table.long_side_seats + ' long';
        }

        if (table.shape === 'square') {
            return 'square, ' + table.square_side_seats + ' per side';
        }

        return 'round';
    }

    function renderSeatMarkers(table, dims, scaleFactor) {
        var positions = seatPositions(table, dims);
        var unassigned = app.strings && app.strings.unassigned ? app.strings.unassigned : 'Unassigned';
        var markerSize = clamp(22 * scaleFactor, 8, 30);
        var markerFont = clamp(11 * scaleFactor, 7, 14);

        return positions.map(function (pos, index) {
            var guestId = table.assignments[index] || '';
            var guestLabel = guestId ? guestLabelById(guestId) : unassigned;
            var markerClass = guestId ? 'is-assigned' : 'is-unassigned';
            var title = $('<div>').text('Seat ' + (index + 1) + ': ' + guestLabel).html();

            return '<span class="sdwc-seat-marker ' + markerClass + '" style="left:' + (pos.x * scaleFactor).toFixed(2) + 'px;top:' + (pos.y * scaleFactor).toFixed(2) + 'px;width:' + markerSize.toFixed(2) + 'px;height:' + markerSize.toFixed(2) + 'px;font-size:' + markerFont.toFixed(2) + 'px;" title="' + title + '">' +
                (index + 1) +
            '</span>';
        }).join('');
    }

    function syncAddFormShapeControls() {
        var shape = sanitizeShape($('#sdwc-table-shape').val());
        var $rectRow = $('#sdwc-rect-seats-row');
        var $squareRow = $('#sdwc-square-seats-row');
        var $seatInput = $('#sdwc-table-seats');
        var $seatLabel = $('label[for="sdwc-table-seats"]');

        if (shape === 'rectangular') {
            $rectRow.show();
            $squareRow.hide();
            $seatInput.prop('readonly', true);
            $seatLabel.text('Seat Count (auto)');
        } else if (shape === 'square') {
            $rectRow.hide();
            $squareRow.show();
            $seatInput.prop('readonly', true);
            $seatLabel.text('Seat Count (auto)');
        } else {
            $rectRow.hide();
            $squareRow.hide();
            $seatInput.prop('readonly', false);
            $seatLabel.text('Seat Count');
        }
    }

    function recalcAddSeatCount() {
        var shape = sanitizeShape($('#sdwc-table-shape').val());
        var $seatInput = $('#sdwc-table-seats');

        if (shape === 'rectangular') {
            var rect = normalizeRectangularSides(
                $('#sdwc-table-short-side').val(),
                $('#sdwc-table-long-side').val(),
                $seatInput.val()
            );

            $('#sdwc-table-short-side').val(rect.short_side_seats);
            $('#sdwc-table-long-side').val(rect.long_side_seats);
            $seatInput.val(rect.seats);
        } else if (shape === 'square') {
            var sideSeats = normalizeSquareSide(
                $('#sdwc-table-square-side').val(),
                $seatInput.val()
            );

            $('#sdwc-table-square-side').val(sideSeats);
            $seatInput.val(sideSeats * 4);
        } else {
            $seatInput.val(clamp(asInt($seatInput.val(), 8), 1, MAX_SEATS));
        }
    }

    function syncLayoutControlsFromState() {
        $('#sdwc-layout-width-feet').val(state.layout.width_feet);
        $('#sdwc-layout-height-feet').val(state.layout.height_feet);
        $('#sdwc-layout-scale').val(state.layout.scale);
        $('#sdwc-layout-scale-value').text(state.layout.scale + '%');
    }

    function readLayoutControlsToState() {
        state.layout.width_feet = clamp(asInt($('#sdwc-layout-width-feet').val(), state.layout.width_feet), 10, 500);
        state.layout.height_feet = clamp(asInt($('#sdwc-layout-height-feet').val(), state.layout.height_feet), 10, 500);
        state.layout.scale = clamp(asInt($('#sdwc-layout-scale').val(), state.layout.scale), 1, 150);
        syncLayoutControlsFromState();
    }

    function addTable() {
        var name = $('#sdwc-table-name').val().trim();
        var shape = sanitizeShape($('#sdwc-table-shape').val());

        if (!name) {
            alert(app.strings && app.strings.addTableError ? app.strings.addTableError : 'Please provide table details and valid seat settings.');
            return;
        }

        recalcAddSeatCount();

        var tableDraft = {
            id: 'table_' + Date.now(),
            name: name,
            shape: shape,
            seats: asInt($('#sdwc-table-seats').val(), 8),
            short_side_seats: asInt($('#sdwc-table-short-side').val(), 1),
            long_side_seats: asInt($('#sdwc-table-long-side').val(), 3),
            square_side_seats: asInt($('#sdwc-table-square-side').val(), 2),
            x: TABLE_EDGE_PADDING + ((state.tables.length % 4) * 36),
            y: TABLE_EDGE_PADDING + ((state.tables.length % 3) * 36),
            assignments: []
        };

        var normalized = normalizeTable(tableDraft, state.tables.length);

        if (!normalized.seats) {
            alert(app.strings && app.strings.addTableError ? app.strings.addTableError : 'Please provide table details and valid seat settings.');
            return;
        }

        state.tables.push(normalized);

        $('#sdwc-table-name').val('');
        renderAll();
    }

    function deleteTable(tableId) {
        state.tables = state.tables.filter(function (table) {
            return table.id !== tableId;
        });
        renderAll();
    }

    function renderTableList() {
        var $list = $('#sdwc-table-list');

        if (!state.tables.length) {
            $list.html('<p class="small text-muted mb-0">No tables added yet.</p>');
            return;
        }

        var guestOptions = guests();
        var assignedMap = assignmentMap();

        var html = state.tables.map(function (table) {
            var rows = '';

            for (var i = 0; i < table.seats; i++) {
                var selected = table.assignments[i] || '';
                var selectedValue = String(selected);
                var optionsHtml = '<option value=""' + (selectedValue === '' ? ' selected' : '') + '>' +
                    (app.strings && app.strings.unassigned ? app.strings.unassigned : 'Unassigned') +
                    '</option>' +
                    guestOptions.map(function (guest) {
                        var guestId = String(guest.id || '');
                        var selectedAttr = guestId === selectedValue ? ' selected' : '';
                        var assignment = assignedMap[guestId];
                        var assignedElsewhere = assignment && !(assignment.tableId === table.id && assignment.seatIndex === i);
                        var disabledAttr = assignedElsewhere ? ' disabled' : '';

                        return '<option value="' + guestId.replace(/"/g, '&quot;') + '"' + selectedAttr + disabledAttr + '>' +
                            $('<div>').text(guest.label).html() +
                            '</option>';
                    }).join('');

                rows += '<div class="sdwc-seat-row">' +
                    '<span class="small text-muted">Seat ' + (i + 1) + '</span>' +
                    '<select class="form-select form-select-sm sdwc-seat-select" data-table-id="' + table.id + '" data-seat-index="' + i + '">' +
                        optionsHtml +
                    '</select>' +
                '</div>';
            }

            var isRound = table.shape === 'round';
            var isRect = table.shape === 'rectangular';
            var isSquare = table.shape === 'square';
            var seatLabel = isRound ? 'Seats' : 'Seats (auto)';
            var seatReadonly = isRound ? '' : ' readonly';

            var sideControls = '';

            if (isRect) {
                sideControls = '<div class="row g-2 mb-2">' +
                    '<div class="col-6">' +
                        '<label class="form-label small mb-1">Short Side</label>' +
                        '<input type="number" min="1" max="15" class="form-control form-control-sm sdwc-short-side" data-table-id="' + table.id + '" value="' + table.short_side_seats + '">' +
                    '</div>' +
                    '<div class="col-6">' +
                        '<label class="form-label small mb-1">Long Side</label>' +
                        '<input type="number" min="1" max="15" class="form-control form-control-sm sdwc-long-side" data-table-id="' + table.id + '" value="' + table.long_side_seats + '">' +
                    '</div>' +
                '</div>';
            }

            if (isSquare) {
                sideControls = '<div class="row g-2 mb-2">' +
                    '<div class="col-12">' +
                        '<label class="form-label small mb-1">Seats Per Side</label>' +
                        '<input type="number" min="1" max="7" class="form-control form-control-sm sdwc-square-side" data-table-id="' + table.id + '" value="' + table.square_side_seats + '">' +
                    '</div>' +
                '</div>';
            }

            return '<div class="sdwc-table-item">' +
                '<div class="d-flex justify-content-between align-items-center mb-2">' +
                    '<strong>' + $('<div>').text(table.name).html() + '</strong>' +
                    '<button type="button" class="btn btn-sm btn-outline-danger sdwc-delete-table" data-table-id="' + table.id + '">Remove</button>' +
                '</div>' +
                '<div class="row g-2 mb-2">' +
                    '<div class="col-6">' +
                        '<label class="form-label small mb-1">Shape</label>' +
                        '<select class="form-select form-select-sm sdwc-shape" data-table-id="' + table.id + '">' +
                            '<option value="round"' + (isRound ? ' selected' : '') + '>Round</option>' +
                            '<option value="rectangular"' + (isRect ? ' selected' : '') + '>Rectangular</option>' +
                            '<option value="square"' + (isSquare ? ' selected' : '') + '>Square</option>' +
                        '</select>' +
                    '</div>' +
                    '<div class="col-6">' +
                        '<label class="form-label small mb-1">' + seatLabel + '</label>' +
                        '<input type="number" min="1" max="30" class="form-control form-control-sm sdwc-seats" data-table-id="' + table.id + '" value="' + table.seats + '"' + seatReadonly + '>' +
                    '</div>' +
                '</div>' +
                sideControls +
                rows +
            '</div>';
        }).join('');

        $list.html(html);
    }

    function renderFloorPlanInto($floor, scalePercent) {
        if (!$floor.length) {
            return;
        }

        if (!state.tables.length) {
            $floor.css({ width: '', height: '' });
            $floor.html('<div class="text-muted small p-3">No tables yet. Add one from the left panel.</div>');
            return;
        }

        var baseSize = layoutBaseSize();
        var scaleFactor = Math.max(0.01, scalePercent / 100);
        var canvasWidth = baseSize.width * scaleFactor;
        var canvasHeight = baseSize.height * scaleFactor;

        $floor.css({
            width: canvasWidth + 'px',
            height: canvasHeight + 'px'
        });

        var html = state.tables.map(function (table) {
            var dims = tableDimensions(table);
            var shapeClass = 'is-round';

            if (table.shape === 'rectangular') {
                shapeClass = 'is-rectangular';
            } else if (table.shape === 'square') {
                shapeClass = 'is-square';
            }

            var width = dims.width * scaleFactor;
            var height = dims.height * scaleFactor;
            var left = table.x * scaleFactor;
            var top = table.y * scaleFactor;
            var nameSize = clamp(13 * scaleFactor, 8, 16);
            var seatSize = clamp(12 * scaleFactor, 7, 14);

            return '<div class="sdwc-table-node ' + shapeClass + '" data-table-id="' + table.id + '" style="left:' + left.toFixed(2) + 'px;top:' + top.toFixed(2) + 'px;width:' + width.toFixed(2) + 'px;height:' + height.toFixed(2) + 'px;">' +
                '<div class="sdwc-seat-markers">' + renderSeatMarkers(table, dims, scaleFactor) + '</div>' +
                '<div class="sdwc-name" style="font-size:' + nameSize.toFixed(2) + 'px;">' + $('<div>').text(table.name).html() + '</div>' +
                '<div class="sdwc-seats" style="font-size:' + seatSize.toFixed(2) + 'px;">' + table.seats + ' seats</div>' +
            '</div>';
        }).join('');

        $floor.html(html);
    }

    function computePrintScalePercent() {
        var base = layoutBaseSize();
        var fitRatio = Math.min(PRINT_MAX_WIDTH_PX / base.width, PRINT_MAX_HEIGHT_PX / base.height);

        if (!isFinite(fitRatio) || fitRatio <= 0) {
            return 100;
        }

        return Math.max(1, Math.min(100, fitRatio * 100));
    }

    function renderFloorPlan() {
        renderFloorPlanInto($('#sdwc-floor-plan'), state.layout.scale);
    }

    function renderPrintFloorPlan() {
        renderFloorPlanInto($('#sdwc-floor-plan-print'), computePrintScalePercent());
    }

    function renderReadOnly() {
        var $view = $('#sdwc-readonly-view');

        if (!state.tables.length) {
            $view.html('<p class="small text-muted mb-0">No seating data yet.</p>');
            return;
        }

        var html = state.tables.map(function (table) {
            var seats = table.assignments.map(function (guestId, index) {
                var label = guestId ? guestLabelById(guestId) : (app.strings && app.strings.unassigned ? app.strings.unassigned : 'Unassigned');
                return '<li class="small">Seat ' + (index + 1) + ': ' + $('<div>').text(label).html() + '</li>';
            }).join('');

            return '<div class="sdwc-readonly-table">' +
                '<div class="fw-bold mb-1">' + $('<div>').text(table.name).html() + ' <span class="text-muted fw-normal">(' + tableShapeSummary(table) + ', ' + table.seats + ' seats)</span></div>' +
                '<ul class="mb-0">' + seats + '</ul>' +
            '</div>';
        }).join('');

        $view.html(html);
    }

    function renderAll() {
        dedupeAssignments();
        renderTableList();
        renderFloorPlan();
        renderReadOnly();
        renderPrintFloorPlan();
    }

    function persist() {
        $.post(app.ajaxUrl, {
            action: 'sdweddingdirectory_seating_chart_save',
            nonce: app.nonce,
            chart_data: JSON.stringify({
                tables: state.tables,
                layout: state.layout
            })
        }).done(function (response) {
            if (response && response.success) {
                alert(app.strings && app.strings.saveSuccess ? app.strings.saveSuccess : 'Saved.');
            } else {
                alert(app.strings && app.strings.saveError ? app.strings.saveError : 'Unable to save.');
            }
        }).fail(function () {
            alert(app.strings && app.strings.saveError ? app.strings.saveError : 'Unable to save.');
        });
    }

    function exportJson() {
        var blob = new Blob([JSON.stringify({ tables: state.tables, layout: state.layout }, null, 2)], { type: 'application/json' });
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'seating-chart.json';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    }

    function bindEvents() {
        $('#sdwc-table-shape').on('change', function () {
            syncAddFormShapeControls();
            recalcAddSeatCount();
        });

        $('#sdwc-table-seats, #sdwc-table-short-side, #sdwc-table-long-side, #sdwc-table-square-side').on('input change', recalcAddSeatCount);

        $('#sdwc-layout-width-feet, #sdwc-layout-height-feet, #sdwc-layout-scale').on('input change', function () {
            readLayoutControlsToState();
            renderFloorPlan();
            renderPrintFloorPlan();
        });

        $('#sdwc-add-table').on('click', addTable);
        $('#sdwc-save-chart').on('click', persist);
        $('#sdwc-print-view').on('click', function () {
            renderPrintFloorPlan();
            window.print();
        });
        $('#sdwc-export-json').on('click', exportJson);

        $(document).on('click', '.sdwc-delete-table', function () {
            deleteTable($(this).data('table-id'));
        });

        $(document).on('change', '.sdwc-shape, .sdwc-seats, .sdwc-short-side, .sdwc-long-side, .sdwc-square-side, .sdwc-seat-select', function () {
            var tableId = $(this).data('table-id');
            var table = state.tables.find(function (item) { return item.id === tableId; });

            if (!table) {
                return;
            }

            if ($(this).hasClass('sdwc-shape')) {
                table.shape = sanitizeShape($(this).val());
                syncTableSeatConfig(table);
            }

            if ($(this).hasClass('sdwc-seats') && table.shape === 'round') {
                table.seats = clamp(asInt($(this).val(), table.seats), 1, MAX_SEATS);
                syncTableSeatConfig(table);
            }

            if ($(this).hasClass('sdwc-short-side')) {
                table.short_side_seats = asInt($(this).val(), table.short_side_seats);
                syncTableSeatConfig(table);
            }

            if ($(this).hasClass('sdwc-long-side')) {
                table.long_side_seats = asInt($(this).val(), table.long_side_seats);
                syncTableSeatConfig(table);
            }

            if ($(this).hasClass('sdwc-square-side')) {
                table.square_side_seats = asInt($(this).val(), table.square_side_seats);
                syncTableSeatConfig(table);
            }

            if ($(this).hasClass('sdwc-seat-select')) {
                var seatIndex = asInt($(this).data('seat-index'), 0);
                var guestId = $(this).val();

                clearDuplicateGuestAssignments(guestId, table.id, seatIndex);
                table.assignments[seatIndex] = guestId;
            }

            renderAll();
        });

        $(document).on('mousedown', '#sdwc-floor-plan .sdwc-table-node', function (event) {
            var $node = $(this);
            var tableId = $node.data('table-id');
            var offset = $node.offset();

            if (!offset) {
                return;
            }

            dragState.active = true;
            dragState.tableId = tableId;
            dragState.offsetX = event.pageX - offset.left;
            dragState.offsetY = event.pageY - offset.top;

            $node.addClass('is-dragging');
            event.preventDefault();
        });

        $(document).on('mousemove', function (event) {
            if (!dragState.active) {
                return;
            }

            var $floor = $('#sdwc-floor-plan');
            var floorOffset = $floor.offset();
            var $viewport = $floor.closest('.sdwc-floor-plan-viewport');
            var scrollLeft = $viewport.length ? $viewport.scrollLeft() : 0;
            var scrollTop = $viewport.length ? $viewport.scrollTop() : 0;

            if (!floorOffset) {
                return;
            }

            var table = state.tables.find(function (item) { return item.id === dragState.tableId; });

            if (!table) {
                return;
            }

            var dims = tableDimensions(table);
            var baseSize = layoutBaseSize();
            var scaleFactor = Math.max(0.01, state.layout.scale / 100);

            var maxX = Math.max(TABLE_EDGE_PADDING, baseSize.width - dims.width - TABLE_EDGE_PADDING);
            var maxY = Math.max(TABLE_EDGE_PADDING, baseSize.height - dims.height - TABLE_EDGE_PADDING);

            var nextScaledX = event.pageX - floorOffset.left - dragState.offsetX + scrollLeft;
            var nextScaledY = event.pageY - floorOffset.top - dragState.offsetY + scrollTop;

            table.x = clamp(nextScaledX / scaleFactor, TABLE_EDGE_PADDING, maxX);
            table.y = clamp(nextScaledY / scaleFactor, TABLE_EDGE_PADDING, maxY);

            renderFloorPlan();
            renderPrintFloorPlan();
        });

        $(document).on('mouseup', function () {
            if (!dragState.active) {
                return;
            }

            dragState.active = false;
            dragState.tableId = '';
            $('#sdwc-floor-plan .sdwc-table-node').removeClass('is-dragging');
            renderReadOnly();
        });

        window.addEventListener('beforeprint', function () {
            renderPrintFloorPlan();
        });
    }

    $(function () {
        state = normalizeData(app.chartData || {});
        bindEvents();
        syncAddFormShapeControls();
        recalcAddSeatCount();
        syncLayoutControlsFromState();
        renderAll();
    });
})(jQuery);
