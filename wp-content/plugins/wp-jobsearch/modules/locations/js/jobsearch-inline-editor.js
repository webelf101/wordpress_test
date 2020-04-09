/*
Bootstable
 @description  Javascript library to make HMTL tables editable, using Bootstrap
 @version 1.1
 @autor Tito Hinostroza
*/
"use strict";
//Global variables
var $ = jQuery;
var params = null;  		//Parameters
var colsEdi = null;
var newColHtml = '<div class="jobsearch-btn-group">' +
    '<button id="bEdit" type="button"  onclick="rowEdit(this);">' +
    '<i class="dashicons dashicons-edit"></i>' +
    '</button>' +
    '<button id="bElim" type="button"   onclick="rowElim(this);">' +
    '<i class="dashicons dashicons-trash" aria-hidden="true"></i>' +
    '</button>' +
    '<button id="bAcep" type="button"  class="save-check" style="display:none;" onclick="rowAcep(this);">' +
    '<i class="dashicons dashicons-yes"></i>' +
    '</button>' +
    '<button id="bCanc" type="button" class="btn btn-sm btn-default" style="display:none;"  onclick="rowCancel(this);">' +
    '<i class="dashicons dashicons-dismiss" aria-hidden="true"></i>' +
    '</button>' +
    '</div>';

var saveColHtml = '<div class="jobsearch-btn-group">' +
    '<button id="bEdit" type="button"  style="display:none;" onclick="rowEdit(this);">' +
    '<i class="dashicons dashicons-edit"></i>' +
    '</button>' +
    '<button id="bElim" type="button"  style="display:none;" onclick="rowElim(this);">' +
    '<i class="dashicons dashicons-trash" aria-hidden="true"></i>' +
    '</button>' +
    '<button id="bAcep" type="button" class="save-check"   onclick="rowAcep(this);">' +
    '<i class="dashicons dashicons-yes"></i>' +
    '</button>' +
    '<button id="bCanc" type="button"  onclick="rowCancel(this);">' +
    '<i class="dashicons dashicons-dismiss" aria-hidden="true"></i>' +
    '</button>' +
    '</div>';
var colEdicHtml = '<td name="buttons">' + newColHtml + '</td>';
var colSaveHtml = '<td name="buttons">' + saveColHtml + '</td>';

$.fn.SetEditable = function (options) {
    var defaults = {
        columnsEd: null,         //Index to editable columns. If null all td editables. Ex.: "1,2,3,4,5"
        $addButton: null,        //Jquery object of "Add" button
        onEdit: function () {
        },   //Called after edition
        onBeforeDelete: function () {
        }, //Called before deletion
        onDelete: function () {
        }, //Called after deletion
        onAdd: function () {
        }     //Called when added a new row
    };
    params = $.extend(defaults, options);
    this.find('thead tr').append('<th name="buttons"></th>');  //encabezado vacío
    this.find('tbody tr').append(colEdicHtml);
    var $tabedi = this;   //Read reference to the current table, to resolve "this" here.
    //Process "addButton" parameter
    if (params.$addButton != null) {
        //Se proporcionó parámetro
        params.$addButton.click(function () {
            rowAddNew($tabedi.attr("id"));
        });
    }
    //Process "columnsEd" parameter
    if (params.columnsEd != null) {
        //Extract felds
        colsEdi = params.columnsEd.split(',');
    }
};

function IterarCamposEdit($cols, tarea) {
//Itera por los campos editables de una fila
    var n = 0;
    $cols.each(function () {
        n++;
        if ($(this).attr('name') == 'buttons') return;  //excluye columna de botones
        if (!EsEditable(n - 1)) return;   //noe s campo editable
        tarea($(this));
    });

    function EsEditable(idx) {
        //Indica si la columna pasada está configurada para ser editable
        if (colsEdi == null) {  //no se definió
            return true;  //todas son editable
        } else {  //hay filtro de campos
//alert('verificando: ' + idx);
            for (var i = 0; i < colsEdi.length; i++) {
                if (idx == colsEdi[i]) return true;
            }
            return false;  //no se encontró
        }
    }
}

function FijModoNormal(but) {
    $(but).parent().find('#bAcep').hide();
    $(but).parent().find('#bCanc').hide();
    $(but).parent().find('#bEdit').show();
    $(but).parent().find('#bElim').show();
    var $row = $(but).parents('tr');  //accede a la fila
    $row.attr('id', '');  //quita marca
}

function FijModoEdit(but) {

    $(but).parent().find('#bAcep').show();
    $(but).parent().find('#bCanc').show();
    $(but).parent().find('#bEdit').hide();
    $(but).parent().find('#bElim').hide();
    var $row = $(but).parents('tr');  //accede a la fila
    $row.attr('id', 'editing');  //indica que está en edición
}

function ModoEdicion($row) {
    if ($row.attr('id') == 'editing') {
        return true;
    } else {
        return false;
    }
}
var _html = '<span class="file-loader"><i class="fa fa-refresh fa-spin"></i></span>';
function rowAcep(but) {
    var $row = $(but).parents('tr');  //accede a la fila
    var $tablename = $(but).parents('table').attr('class');
    var $cols = $row.find('td');  //lee campos
    if (!ModoEdicion($row)) return;  //Ya está en edición
    var _flag = true;
    var _counter = 0;
    IterarCamposEdit($cols, function ($td) {
        var cont = $td.find('input').val();
        if (_counter == 0) {
            if (cont == '') {
                _flag = false;
                $row.addClass('loc-error');
            } else {
                _flag = true;
                $row.removeClass('loc-error');
            }
        }

        if (_counter == 1 && $tablename == 'table country-table-detail' && _flag == true) {
            if (cont == '') {
                _flag = false;
                $row.addClass('loc-error');
                alert(jobsearch_location_common_text.req_cntry);
            } else if (isNaN(cont) == false) {
                _flag = false;
                $row.addClass('loc-error');
                alert(jobsearch_location_common_text.req_num)
            } else if (cont.length > 3) {
                _flag = false;
                $row.addClass('loc-error');
                alert(jobsearch_location_common_text.req_chars);
            } else {
                _flag = true;
                $row.removeClass('loc-error');

            }

            if (cont != cont.toUpperCase()) {
                _flag = false;
                $row.addClass('loc-error');
                alert(jobsearch_location_common_text.req_cntry_code_uppercase)
            }

            if (_flag == true) {
                var request = jQuery.ajax({
                    url: ajaxurl,
                    method: "POST",
                    data: {
                        country_code: cont.toUpperCase(),
                        action: 'jobsearch_check_state_dir',
                    },
                    dataType: "json"
                });
                request.done(function (response) {
                    if ('undefined' !== typeof response.country_code) {
                        _flag = false;
                        $row.addClass('loc-error');
                        alert("Country code '" + response.country_code + "'  already exists.")
                    }
                });
                request.fail(function (jqXHR, textStatus) {
                    alert(textStatus)
                });
            }
        }

        if (_flag == true) {
            if (_counter == 2 && $tablename == 'table country-table-detail') {
                if (isNaN(cont) == true || cont == '') {
                    alert(jobsearch_location_common_text.req_poplation);
                    $row.addClass('loc-error');
                } else {
                    $row.removeClass('loc-error')
                }
            }
        }
        $td.html(cont);  //fija contenido y elimina controles
        _counter++;
    });
    if ($row.hasClass('loc-error')) {
        if ($tablename == 'table country-table-detail') {
            $('#submit_country_detail').prop('disabled', true);
            $('#submit_country_detail').addClass('loc-disabled');
        } else if ($tablename == 'table state-table-detail') {
            $('#submit_states_detail').prop('disabled', true);
            $('#submit_states_detail').addClass('loc-disabled');
        } else if ($tablename == 'table cities-table-detail') {
            $('#submit_cities_detail').prop('disabled', true);
            $('#submit_cities_detail').addClass('loc-disabled');
        }
    } else {
        if ($tablename == 'table country-table-detail') {
            $('#submit_country_detail').prop('disabled', false);
            $('#submit_country_detail').removeClass('loc-disabled');
        } else if ($tablename == 'table state-table-detail') {
            $('#submit_states_detail').prop('disabled', false);
            $('#submit_states_detail').removeClass('loc-disabled');
        } else if ($tablename == 'table cities-table-detail') {
            $('#submit_cities_detail').prop('disabled', false);
            $('#submit_cities_detail').removeClass('loc-disabled');
        }
    }
    FijModoNormal(but);
    params.onEdit($row);
}

function rowCancel(but) {
//Rechaza los cambios de la edición
    var $row = $(but).parents('tr');  //accede a la fila
    var $cols = $row.find('td');  //lee campos
    if (!ModoEdicion($row)) return;  //Ya está en edición
    //Está en edición. Hay que finalizar la edición
    IterarCamposEdit($cols, function ($td) {  //itera por la columnas
        var cont = $td.find('div').html(); //lee contenido del div
        $td.html(cont);  //fija contenido y elimina controles
    });
    FijModoNormal(but);
}

function rowEdit(but) {
    var $td = $("tr[id='editing'] td");
    rowAcep($td);
    var $row = $(but).parents('tr');
    var $cols = $row.find('td');
    if (ModoEdicion($row)) return;  //Ya está en edición
    //Pone en modo de edición
    IterarCamposEdit($cols, function ($td) {  //itera por la columnas
        var cont = $td.html(); //lee contenido
        var div = '<div style="display: none;">' + cont + '</div>';  //guarda contenido
        var input = '<input class="form-control input-sm"  value="' + cont + '">';
        $td.html(div + input);  //fija contenido
    });
    FijModoEdit(but);
}

function rowElim(but) {  //Elimina la fila actual
    var $row = $(but).parents('tr');  //accede a la fila
    var $tablename = $(but).parents('table').attr('class');

    if ($tablename == 'table country-table-detail') {
        $('#submit_country_detail').prop('disabled', false);
        $('#submit_country_detail').removeClass('loc-disabled');
    } else if ($tablename == 'table state-table-detail') {
        $('#submit_states_detail').prop('disabled', false);
        $('#submit_states_detail').removeClass('loc-disabled');
    } else if ($tablename == 'table cities-table-detail') {
        $('#submit_cities_detail').prop('disabled', false);
        $('#submit_cities_detail').removeClass('loc-disabled');
    }
    params.onBeforeDelete($row);
    $row.remove();
    params.onDelete();
}

function rowAddNew(tabId) {  //Agrega fila a la tabla indicada.
    var $tab_en_edic = $("#" + tabId);  //Table to edit
    var $filas = $tab_en_edic.find('tbody tr');
    if ($filas.length == 0) {
        //No hay filas de datos. Hay que crearlas completas
        var $row = $tab_en_edic.find('thead tr');  //encabezado
        var $cols = $row.find('th');  //lee campos
        //construye html
        var htmlDat = '';
        $cols.each(function () {
            if ($(this).attr('name') == 'buttons') {
                //Es columna de botones
                htmlDat = htmlDat + colEdicHtml;  //agrega botones
            } else {
                htmlDat = htmlDat + '<td></td>';
            }
        });
        $tab_en_edic.find('tbody').append('<tr>' + htmlDat + '</tr>');
    } else {
        //Hay otras filas, podemos clonar la última fila, para copiar los botones
        var $ultFila = $tab_en_edic.find('tr:last');
        $ultFila.clone().appendTo($ultFila.parent());
        $tab_en_edic.find('tr:last').attr('id', 'editing');
        $ultFila = $tab_en_edic.find('tr:last');
        var $cols = $ultFila.find('td');  //lee campos

        $cols.each(function () {
            if ($(this).attr('name') == 'buttons') {
                //Es columna de botones
            } else {
                var div = '<div style="display: none;"></div>';  //guarda contenido
                var input = '<input class="form-control input-sm"  value="">';

                $(this).html(div + input);  //limpia contenido
            }
        });
        $ultFila.find('td:last').html(saveColHtml);

    }
    params.onAdd();
}

function TableToCSV(tabId, separator) {  //Convierte tabla a CSV
    var datFil = '';
    var tmp = '';
    var $tab_en_edic = $("#" + tabId);  //Table source
    $tab_en_edic.find('tbody tr').each(function () {
        //Termina la edición si es que existe
        if (ModoEdicion($(this))) {
            $(this).find('#bAcep').click();  //acepta edición
        }
        var $cols = $(this).find('td');  //lee campos
        datFil = '';
        $cols.each(function () {
            if ($(this).attr('name') == 'buttons') {
                //Es columna de botones
            } else {
                datFil = datFil + $(this).html() + separator;
            }
        });
        if (datFil != '') {
            datFil = datFil.substr(0, datFil.length - separator.length);
        }
        tmp = tmp + datFil + '\n';
    });
    return tmp;
}

///////////////// Jobsreach location functions///////////////////////
var selector;
function readSingleCityStateFile(file, table_selector) {
    var $ = jQuery;
    selector = table_selector.find("table tbody");
    rawFile = new XMLHttpRequest();
    rawFile.open("GET", file, false);
    rawFile.onreadystatechange = function () {
        if (rawFile.readyState === 4) {
            if (rawFile.status === 200 || rawFile.status == 0) {
                var _result_states = JSON.parse(rawFile.responseText);

                selector.html('');
                $.each(_result_states.result, function (index, element) {
                    var _newColHtml = '<tr><td>' + element + '</td>' +
                        '<td name="buttons"><div class="jobsearch-btn-group">' +
                        '<button id="bEdit" type="button"  onclick="rowEdit(this);">' +
                        '<i class="dashicons dashicons-edit"></i>' +
                        '</button>' +
                        '<button id="bElim" type="button"   onclick="rowElim(this);">' +
                        '<i class="dashicons dashicons-trash" aria-hidden="true"></i>' +
                        '</button>' +
                        '<button id="bAcep" type="button" class="save-check"  style="display:none;" onclick="rowAcep(this);">' +
                        '<i class="dashicons dashicons-yes"></i>' +
                        '</button>' +
                        '<button id="bCanc" type="button" class="btn btn-sm btn-default" style="display:none;"  onclick="rowCancel(this);">' +
                        '<i class="dashicons dashicons-dismiss" aria-hidden="true"></i>' +
                        '</button>' +
                        '</div></td></tr>';

                    selector.append(_newColHtml);
                })
            }
        }
    }
    rawFile.send(null);
}


function readsingleCountryData(file, country_code) {
    var $ = jQuery;
    selector = jQuery(".country-table").find("table tbody");

    rawFile = new XMLHttpRequest();
    rawFile.open("GET", file, false);
    rawFile.onreadystatechange = function () {
        if (rawFile.readyState === 4) {
            if (rawFile.status === 200 || rawFile.status == 0) {
                var _result_countries = JSON.parse(rawFile.responseText);
                jQuery('.country-table').removeClass('loc-hidden');
                $.each(_result_countries, function (index, element) {
                    if (country_code == element.code) {

                        var _newColHtml = '<tr><td>' + element.name + '</td><td>' + element.code + '</td><td>' + element.population + '</td>' +
                            '<td name="buttons"><div class="jobsearch-btn-group">' +
                            '<button id="bEdit" type="button"  onclick="rowEdit(this);">' +
                            '<i class="dashicons dashicons-edit"></i>' +
                            '</button>' +
                            '<button id="bElim" type="button"   onclick="rowElim(this);">' +
                            '<i class="dashicons dashicons-trash" aria-hidden="true"></i>' +
                            '</button>' +
                            '<button id="bAcep" type="button" class="save-check"  style="display:none;" onclick="rowAcep(this);">' +
                            '<i class="dashicons dashicons-yes"></i>' +
                            '</button>' +
                            '<button id="bCanc" type="button" class="btn btn-sm btn-default" style="display:none;"  onclick="rowCancel(this);">' +
                            '<i class="dashicons dashicons-dismiss" aria-hidden="true"></i>' +
                            '</button>' +
                            '</div></td></tr>';
                        selector.html('');
                        selector.append(_newColHtml)
                    }
                })
            }
        }
    }
    rawFile.send(null);
}

jQuery(function () {
    var $ = jQuery;
    var request, ar_lines, each_data_value, td, i;

    ////////// Countries Editable ////////////////////
    jQuery('#makeEditableCountries').SetEditable({$addButton: jQuery('#add_country')});
    jQuery('#submit_country_detail').on('click', function () {
        jQuery("#submit_country_detail").html(_html);
        selector = jQuery('#countryId');
        var _country_index = jQuery('#countryId option:selected').data('index');
        var _single_country_code = jQuery('#countryId option:selected').attr('code');

        td = TableToCSV('makeEditableCountries', ',');
        ar_lines = td.split("\n");
        each_data_value = [];
        var _flag = false;

        for (i = 0; i < ar_lines.length; i++) {
            var _countries_detail = ar_lines[i].split(",");
            each_data_value.push({
                "name": _countries_detail[0],
                "code": _countries_detail[1],
                "population": _countries_detail[2],
            })
        }

        selector.html('');
        selector.append('<option>' + jobsearch_location_common_text.pls_wait + '</option>');
        rawFile = new XMLHttpRequest();
        rawFile.open("GET", country_json_file, false);
        rawFile.onreadystatechange = function () {
            if (rawFile.readyState === 4) {
                if (rawFile.status === 200 || rawFile.status == 0) {
                    var _result_countries = JSON.parse(rawFile.responseText);
                    request = jQuery.ajax({
                        url: ajaxurl,
                        method: "POST",
                        data: {
                            current_countries: _result_countries,
                            updated_countries: each_data_value,
                            country_to_update: _country_index,
                            single_country_code: _single_country_code,
                            action: 'jobsearch_update_country',
                        },
                        dataType: "json"
                    });
                    request.done(function (response) {
                        if ('undefined' !== typeof response.status && response.status == 'data_updated') {

                            jQuery("#submit_country_detail").remove('span').text(jobsearch_location_common_text.cntry_success);

                            setTimeout(function () {
                                jQuery("#submit_country_detail").text('').text(jobsearch_location_common_text.sav_contry);
                                selector.html('');
                                api_scrapper.readCountryFile(country_json_file, jQuery('#countryId'), '');
                            }, 1500)
                        } else {
                            jQuery("#submit_country_detail").remove('span').text(jobsearch_location_common_text.sav_contry);
                            setTimeout(function () {
                                selector.html('');
                                api_scrapper.readCountryFile(country_json_file, jQuery('#countryId'), '');
                                jQuery(".state-wrapper").addClass('loc-hidden');
                                jQuery(".cities-wrapper").addClass('loc-hidden');
                            }, 1500)
                        }
                    });
                    request.fail(function (jqXHR, textStatus) {
                        alert(textStatus)
                    });
                }
            }
        }
        rawFile.send(null);
    });
    ////////////////States Editable////////////////////
    jQuery('#makeEditableStates').SetEditable({$addButton: jQuery('#add_state')});
    jQuery('#submit_states_detail').on('click', function () {
        jQuery("#submit_states_detail").html(_html);

        selector = jQuery("#stateId");
        var _country_code = jQuery('#countryId option:selected').attr('code');

        td = TableToCSV('makeEditableStates', ',');
        if ($.trim(td) == 'Enter Any State') {
            alert(jobsearch_location_common_text.req_state)
            jQuery("#submit_states_detail").remove('span').text(jobsearch_location_common_text.save_states);
            return false;
        }
        ar_lines = td.split("\n");
        each_data_value = [];
        for (i = 0; i < ar_lines.length; i++) {
            var _state_detail = ar_lines[i].split(",");
            each_data_value.push({
                "state_name": _state_detail[0],
            })
        }

        selector.html('');
        selector.append('<option>' + jobsearch_location_common_text.pls_wait + '</option>');
        var _file_name = country_json_files_loc + "" + _country_code + '/' + _country_code + '-states.json?param=' + random_num;

        rawFile = new XMLHttpRequest();
        rawFile.open("GET", _file_name, false);
        rawFile.onreadystatechange = function () {
            if (rawFile.readyState === 4) {
                if (rawFile.status === 200 || rawFile.status == 0) {
                    var _result_states = JSON.parse(rawFile.responseText);
                    request = jQuery.ajax({
                        url: ajaxurl,
                        method: "POST",
                        data: {
                            current_states: _result_states,
                            updated_states: each_data_value,
                            country_code: _country_code,
                            action: 'jobsearch_add_new_states',
                        },
                        dataType: "json"
                    });
                    request.done(function (response) {
                        if ('undefined' !== typeof response.status && response.status == 'data_updated') {
                            jQuery("#submit_states_detail").text('').text(jobsearch_location_common_text.state_success);
                            setTimeout(function () {

                                jQuery("#submit_states_detail").remove('span').text(jobsearch_location_common_text.save_states);
                                selector.html('');
                                api_scrapper.readStateFile(_file_name, jQuery('#stateId'));
                            }, 1600)
                        }
                    });
                    request.fail(function (jqXHR, textStatus) {
                        alert(textStatus)
                    });
                }
            }
        }
        rawFile.send(null);
    });
    ////////////////Cities Editable////////////////////
    jQuery('#makeEditableCities').SetEditable({$addButton: jQuery('#add_cities')});
    jQuery('#submit_cities_detail').on('click', function () {
        jQuery("#submit_cities_detail").html(_html);

        var _country_code = jQuery('#countryId option:selected').attr('code');

        var _state_name = jQuery("#stateId");
        td = TableToCSV('makeEditableCities', ',');
        if ($.trim(td) == 'Enter Any City') {
            alert(jobsearch_location_common_text.req_city);
            jQuery("#submit_cities_detail").remove('span').text(jobsearch_location_common_text.sav_city);
            return false;
        }
        ar_lines = td.split("\n");
        each_data_value = [];
        for (i = 0; i < ar_lines.length; i++) {
            var _cities_detail = ar_lines[i].split(",");
            each_data_value.push({
                "cities_name": _cities_detail[0],
            })
        }

        request = jQuery.ajax({
            url: ajaxurl,
            method: "POST",
            data: {
                updated_cities: each_data_value,
                country_code: _country_code,
                states_name: _state_name.val(),
                action: 'jobsearch_add_new_cities',
            },
            dataType: "json"
        });
        request.done(function (response) {
            if ('undefined' !== typeof response.status && response.status == 'data_updated') {
                jQuery("#submit_cities_detail").remove('span').text(jobsearch_location_common_text.city_success);

                setTimeout(function () {
                    jQuery("#submit_cities_detail").remove('span').text(jobsearch_location_common_text.sav_city);
                }, 1600)
            }
        });
        request.fail(function (jqXHR, textStatus) {
            alert(textStatus)
        });
    });
})