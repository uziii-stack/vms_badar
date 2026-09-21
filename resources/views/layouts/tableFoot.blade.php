<!-- <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
{{-- <script src="https://unpkg.com/bootstrap-table@1.22.1/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.22.1/dist/extensions/export/bootstrap-table-export.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.22.1/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script> --}}


<script src="https://unpkg.com/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://unpkg.com/jspdf-autotable@3.5.31/dist/jspdf.plugin.autotable.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.23.0/bootstrap-table.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.29.0/tableExport.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/extensions/export/bootstrap-table-export.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/extensions/print/bootstrap-table-print.min.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.29.0/libs/jsPDF/jspdf.umd.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.29.0/libs/html2canvas/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/akottr/dragtable@master/jquery.dragtable.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/extensions/reorder-columns/bootstrap-table-reorder-columns.min.js"></script>-->
<script rel="preload" src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script rel="preload" src="https://unpkg.com/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script rel="preload" src="https://unpkg.com/jspdf-autotable@3.5.31/dist/jspdf.plugin.autotable.js"></script>
<script rel="preload" src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.25.0/dist/bootstrap-table.min.js"></script>
<script rel="preload" src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.29.0/tableExport.min.js"></script>
<script rel="preload" src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/extensions/export/bootstrap-table-export.min.js"></script> 
<script rel="preload" src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script> 
<script rel="preload" src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/extensions/print/bootstrap-table-print.min.js"></script>
<script rel="preload" src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.29.0/libs/html2canvas/html2canvas.min.js"></script>
<script rel="preload" src="https://cdn.jsdelivr.net/npm/jqueryui@1.11.1/jquery-ui.min.js"></script>
<script rel="preload" src="https://cdn.jsdelivr.net/gh/akottr/dragtable@master/jquery.dragtable.js"></script>
<script rel="preload" src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.0/dist/extensions/reorder-columns/bootstrap-table-reorder-columns.min.js"></script>
<script>
    if (window.jQuery && $.fn.bootstrapTable) {
        $.extend($.fn.bootstrapTable.defaults, {
            exportDataType: 'basic',
            exportOptions: {
                htmlContent: true
            }
        });

        function readBootstrapTableField(row, field) {
            return field.split('.').reduce(function(value, key) {
                return value && value[key] !== undefined ? value[key] : '';
            }, row);
        }

        function cleanBootstrapTableExportValue(value) {
            if (Array.isArray(value)) {
                return value.map(function(item) {
                    if (item && typeof item === 'object') {
                        return item.name || item.title || item.uid || item.id || JSON.stringify(item);
                    }

                    return item;
                }).join(', ');
            }

            if (value && typeof value === 'object') {
                return value.name || value.title || value.uid || value.id || JSON.stringify(value);
            }

            return value === null || value === undefined ? '' : value;
        }

        function csvEscape(value) {
            var text = String(cleanBootstrapTableExportValue(value)).replace(/<[^>]*>/g, '').trim();

            return '"' + text.replace(/"/g, '""') + '"';
        }

        window.downloadFilteredBootstrapTableCsv = function(table) {
            var $table = $(table);
            var options = $table.bootstrapTable('getOptions');
            var columns = $table.bootstrapTable('getVisibleColumns')
                .filter(function(column) {
                    return column.field && !column.checkbox && !column.printIgnore;
                });
            var rows = $table.bootstrapTable('getData', {
                useCurrentPage: false,
                includeHiddenRows: false
            });

            if (!rows.length) {
                alert('No filtered rows available to download.');
                return;
            }

            var csv = [
                columns.map(function(column) {
                    return csvEscape(column.title || column.field);
                }).join(',')
            ];

            rows.forEach(function(row, rowIndex) {
                csv.push(columns.map(function(column) {
                    if (column.field === 'SNO') {
                        return csvEscape(rowIndex + 1);
                    }

                    return csvEscape(readBootstrapTableField(row, column.field));
                }).join(','));
            });

            var blob = new Blob(["\ufeff" + csv.join("\r\n")], {
                type: 'text/csv;charset=utf-8;'
            });
            var link = document.createElement('a');
            var fileName = (options.exportOptions && options.exportOptions.fileName) || document.title || 'table-data';

            link.href = URL.createObjectURL(blob);
            link.download = fileName + '-filtered.csv';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(link.href);
        };

        function addFilteredCsvButton(table) {
            var $table = $(table);
            var $toolbar = $table.closest('.bootstrap-table').find('.fixed-table-toolbar .columns').first();

            if (!$toolbar.length || $toolbar.find('.download-filtered-csv').length) {
                return;
            }

            $('<button type="button" class="btn btn-secondary download-filtered-csv" title="Download filtered CSV">Filtered CSV</button>')
                .on('click', function() {
                    window.downloadFilteredBootstrapTableCsv($table);
                })
                .prependTo($toolbar);
        }

        $(document).on('post-header.bs.table load-success.bs.table reset-view.bs.table', function(event) {
            addFilteredCsvButton(event.target);
        });

        $(function() {
            $('table[data-toggle="table"]').each(function() {
                addFilteredCsvButton(this);
            });
        });
    }
</script>
