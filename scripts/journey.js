import $ from 'jquery';
import 'jquery-deserialize';
import '@fortawesome/fontawesome-free/css/fontawesome.min.css';
import '@fortawesome/fontawesome-free/css/solid.min.css';
import '@eonasdan/tempus-dominus/dist/css/tempus-dominus.css';
import * as tempusDominus from '@eonasdan/tempus-dominus';
import './journey.css';

(
    function () {
        function show_saved_query_message() {
            $('#saved_journeys_message')
                .text(
                    'Number of saved journeys: '
                    + ((localStorage.getItem('journeys') ?? '').match(/\n/g) ?? []).length
                );
        }

        document.addEventListener(
            'DOMContentLoaded'
            , function () {
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.register('/journey_service_worker.js', {scope : document.getElementById('journey_script').getAttribute('data-scope')});
                    if (navigator.serviceWorker.controller === null) {
                        navigator.serviceWorker.ready.then(() => location.reload());
                    }
                }

                ['boarding_time', 'alighting_time'].forEach(
                    function (time_field_id) {
                        const button_id = time_field_id + '_button';
                        const offset_field_id = time_field_id + '_offset';

                        function get_offset() {
                            return (new Date).getTimezoneOffset() / -60;
                        }

                        document.getElementById(offset_field_id).value = get_offset();
                        const time_field = document.getElementById(time_field_id);
                        function styleToMap(style) {
                            return new Map(Array.from(style).map(key => [key, style[key]]));
                        }
                        const original_style = styleToMap(window.getComputedStyle(time_field));
                        time_field.setAttribute('readonly', 'readonly');
                        const modified_style = styleToMap(window.getComputedStyle(time_field));
                        for (const property of original_style.keys()) {
                            if (modified_style.get(property) !== original_style.get(property)) {
                                time_field.style[property] = original_style.get(property);
                            }
                        }

                        const parent = time_field.parentElement;
                        parent.setAttribute('data-td-target-toggle', `#${time_field_id}`);
                        parent.setAttribute('data-td-target-input', `#${time_field_id}`);
                        const picker = new tempusDominus.TempusDominus(
                            parent
                            , {
                                display : {
                                    icons : {
                                        today: 'fa-solid fa-circle',
                                    },
                                    buttons : {
                                        today : true,
                                        clear : true,
                                        close : true,
                                    },
                                    toolbarPlacement : 'top',
                                    sideBySide : false,
                                    viewMode : 'clock',
                                    components : {
                                        seconds : true,
                                    }
                                },
                                localization : {
                                    hourCycle : 'h23',
                                    format : 'yyyy-MM-dd[T]HH:mm:ss'
                                },
                                useCurrent : false,
                            }
                        );
                        picker.subscribe(
                            tempusDominus.Namespace.events.show
                            , () => {
                                if (time_field.value === '') {
                                    picker.dates.setValue(new tempusDominus.DateTime());
                                }
                                picker.dates.setFromInput(time_field.value);
                            }
                        )
                        document.getElementById(button_id).addEventListener(
                            'click'
                            , function () {
                                function format_date(date) {
                                    const year = date.getFullYear();
                                    const month = date.getMonth() + 1;
                                    const day = date.getDate();
                                    const hour = date.getHours();
                                    const minute = date.getMinutes();
                                    const second = date.getSeconds();

                                    return year
                                        + '-'
                                        + (month < 10 ? '0' : '')
                                        + month
                                        + '-'
                                        + (day < 10 ? '0' : '')
                                        + day
                                        + 'T'
                                        + (hour < 10 ? '0' : '')
                                        + hour
                                        + ':'
                                        + (minute < 10 ? '0' : '')
                                        + minute
                                        + ':'
                                        + (second < 10 ? '0' : '')
                                        + second;
                                }

                                time_field.value
                                    = format_date(new Date());
                                document.getElementById(offset_field_id).value
                                    = get_offset();
                            }
                        )
                    }
                );
                show_saved_query_message();
            }
        );

        $(document).ready(
            function () {
                $('#ticket_table select').change(
                    function () {
                        $(this).siblings('details').find('input')
                            .prop('disabled', this.value !== '')
                    }
                );
                const $ticket_description_inputs = $('#ticket_table input.ticket_description');
                const $form = $('#form');
                const $readonly_elements = $('[readonly]');
                $readonly_elements.on('invalid', () => {
                    alert('Please fill in the time.');
                });
                $('input').on('invalid', () => {
                    $readonly_elements.attr('readonly', 'readonly');
                });
                $('#submit_button,#hidden_submit_button').click(
                    function () {
                        $('#journey_table [data-required="required"]').attr('required', 'required');
                        $readonly_elements.removeAttr('readonly');
                        $ticket_description_inputs
                            .filter((_, element) => (element.value !== ''))
                            .parent()
                            .parent()
                            .siblings()
                            .find('[data-required="ticket"]')
                            .attr('required', 'required');
                        $ticket_description_inputs
                            .filter((_, element) => (element.value === ''))
                            .parent()
                            .parent()
                            .siblings()
                            .find('[data-required="ticket"]')
                            .removeAttr('required')
                        localStorage.setItem('current', $('#last_journey').attr('data-serial'));
                        localStorage.setItem('inserting', $form.serialize());
                    }
                );
                const inserting = localStorage.getItem('inserting');
                if (inserting !== null && $('#last_journey').attr('data-serial') === localStorage.getItem('current')) {
                    $form.deserialize(inserting);
                }
                $('#last_button').click(
                    function () {
                        $('#journey_table [data-required]').removeAttr('required');
                        $('#ticket_table [data-required]').removeAttr('required');
                    }
                );
                $('#push_button').click(
                    function () {
                        const serialised_data = localStorage.getItem('journeys') ?? '';
                        localStorage.setItem('journeys', serialised_data + $form.serialize() + "\n");
                        show_saved_query_message();
                        $form[0].reset();
                        $('#ticket_table select').change();
                    }
                );
                $('#pop_button').click(
                    function () {
                        const serialised_data = localStorage.getItem('journeys') ?? '';
                        const index = serialised_data.indexOf("\n");
                        if (index === -1) {
                            alert('Queue is empty.');
                            return false;
                        }
                        const entry = serialised_data.substr(0, index);
                        localStorage.setItem('journeys', serialised_data.substring(index + 1));
                        show_saved_query_message();
                        $("input[type='checkbox']").prop('checked', false);
                        $form.deserialize(entry);
                        $('#ticket_table select').change();
                        return null;
                    }
                );
                const setDisableOnInput = () => $('#last_button, #submit_button, #hidden_submit_button').attr('disabled', !window.navigator.onLine);
                setDisableOnInput();
                window.addEventListener('online', setDisableOnInput);
                window.addEventListener('offline', setDisableOnInput);
            }
        );
    }
)();
