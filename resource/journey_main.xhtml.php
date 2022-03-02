<?php
declare(strict_types = 1);

use Miklcct\JourneyRecorder\JourneyView;
use function Miklcct\JourneyRecorder\format_currency;
use function Miklcct\ThinPhpApp\Escaper\xml;
use function Miklcct\ThinPhpApp\Utility\nullable;

/** @var JourneyView $this */
?>
<main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-deserialize@2.0.0/src/jquery.deserialize.min.js"></script>
    <script src="/scripts/journey.js"></script>
    <link rel="stylesheet" href="/stylesheets/journey.css"/>
    <?php
if ($this->journey !== NULL) {
    $array = array_filter($this->journey->toArray(), static fn(string $key) => $key !== 'tickets', ARRAY_FILTER_USE_KEY);
?>
    <p>Last inserted journey:</p>
    <table>
        <colgroup>
            <col class="header"/>
            <col class="content"/>
        </colgroup>
<?php
    foreach ($array as $key => $value) {
?>
        <tr>
            <th>
                <?= xml($key) ?>
            </th>
            <td>
                <?= xml(
                    match($key) {
                        'distance' => $value === null ? '' : sprintf('%.2f', $value),
                        'speed' => $value === null ? '' : sprintf('%.0f', $value),
                        default => $value,
                    }
                ) ?>
            </td>
        </tr>
<?php
    }
?>
    </table>
<?php
    if ($this->journey->tickets !== []) {
?>
    <p>Tickets used:</p>
    <table id="tickets_used">
        <colgroup>
            <col class="distance_column" />
            <col class="distance_column" />
            <col class="currency_column" />
            <col />
            <col />
        </colgroup>
        <thead>
            <tr><th rowspan="2">From</th><th rowspan="2">To</th><th colspan="3">Ticket</th></tr>
            <tr><th>Price</th><th>Carnet</th><th>Expired</th></tr>
        </thead>
        <tbody>
<?php
        foreach ($this->journey->tickets as $ticket) {
?>
            <tr>
                <td rowspan="2" class="distance_column number"><?= xml(nullable($ticket->coverFrom, fn($distance) => sprintf('%.2f', $distance)) ?? '') ?></td>
                <td rowspan="2" class="distance_column number"><?= xml(nullable($ticket->coverTo, fn($distance) => sprintf('%.2f', $distance)) ?? '') ?></td>
                <td colspan="3"><?= xml($ticket->description) ?></td>
            </tr>
            <tr>
                <td><?= xml($ticket->currencyCode)?> <span class="number"><?= xml(format_currency($ticket->currencyCode, $ticket->price, true)) ?></span></td>
                <td><?= xml(sprintf('#%d/%d', $ticket->carnetsUsed + 1, $ticket->carnets)) ?></td>
                <td><?= xml($ticket->expired ? '✓' : '') ?></td>
            </tr>
<?php
        }
?>
        </tbody>
    </table>
<?php
    }
}
?>
    <form id="form" action="" method="post">
        <div id="top_buttons">
            <input id="hidden_submit_button" type="submit" />
            <button id="last_button" type="submit" name="last">
                Get the last inserted journey
            </button>
            <button id="pop_button" type="button">Pop from queue</button>
            <span id="saved_journeys_message"></span>
        </div>
        <p>Insert a new journey:</p>
        <table id="journey_table">
            <colgroup>
                <col class="header"/>
                <col class="content"/>
            </colgroup>
            <tr>
                <th><label for="type">type</label></th>
                <td>
                    <select id="type" name="type" data-required="required">
                        <option value=""></option>
                        <optgroup label="Rail">
                            <option value="Train">Train</option>
                            <option value="Tram">Tram</option>
                            <option value="Funicular">Funicular</option>
                            <option value="Cable Car">Cable Car</option>
                        </optgroup>
                        <optgroup label="Road">
                            <option value="BRT">BRT</option>
                            <option value="Bus">Bus</option>
                            <option value="Trolleybus">Trolleybus</option>
                            <option value="Share taxi">Share taxi</option>
                        </optgroup>
                        <optgroup label="Air &amp; Water">
                            <option value="Ferry">Ferry</option>
                            <option value="Aeroplane">Aeroplane</option>
                            <option value="Helicopter">Helicopter</option>
                        </optgroup>
                    </select>
                </td>
            </tr>
<?php
$show_input = function (string $field, bool $required) {
    $id = str_replace(' ', '_', $field);
?>
            <tr>
                <th><label for="<?= xml($id) ?>"><?= xml($field) ?></label></th>
                <td>
                    <input
                        id="<?= xml($id) ?>"
                        name="<?= xml($field) ?>"
                        type="text"
                        maxlength="255"
                        <?= $required ? 'data-required="required"' : '' ?>
                    />
                </td>
            </tr>
<?php
};

$show_input('network', TRUE);
$show_input('route', FALSE);
$show_input('destination', FALSE);

$show_time_input = function (string $time_field, string $time_offset_field) {
    $time_field_id = str_replace(' ', '_', $time_field);
    $time_offset_field_id = str_replace(' ', '_', $time_offset_field);
?>
    <tr>
        <th><label for="<?= xml($time_field_id) ?>"><?= xml($time_field) ?></label></th>
        <td>
            <input
                    id="<?= xml($time_field_id) ?>"
                    name="<?= xml($time_field_id) ?>"
                    type="datetime-local"
                    data-required="required"
            />
            <button type="button" id="<?= xml("{$time_field_id}_button") ?>">•</button>
        </td>
    </tr>
    <tr>
        <th><label for="<?= xml($time_offset_field_id) ?>"><?= xml($time_offset_field) ?></label></th>
        <td>
            <input
                    id="<?= xml($time_offset_field_id) ?>"
                    name="<?= xml($time_offset_field_id) ?>"
                    type="number"
                    min="-12"
                    max="14"
                    step="0.25"
                    data-required="required"
            />
            hours from UTC
        </td>
    </tr>
<?php
};

$show_input('boarding place', TRUE);
$show_time_input('boarding time', 'boarding time offset');
$show_input('cabin number', FALSE);
$show_input('alighting place', TRUE);
$show_time_input('alighting time', 'alighting time offset');

?>
            <tr>
                <th><label for="distance">distance</label></th>
                <td>
                    <input
                        id="distance"
                        name="distance"
                        type="number"
                        min="0.00"
                        step="0.01"
                    /> km
                </td>
            </tr>
        </table>
<?php
for ($i = 0; $i < 5; ++$i) {
    if ($i === 1) {
?>
        <details>
            <summary>Additional tickets</summary>
<?php
    }
?>
        <table>
            <colgroup>
                <col class="header"/>
                <col class="content"/>
            </colgroup>
            <tr>
                <th><label for="ticket_uses_<?= xml($i) ?>_serial">ticket</label></th>
                <td>
                    <select id="ticket_uses_<?= xml($i) ?>_serial" name="ticket uses[<?= xml($i) ?>][serial]">
                        <option value=""></option>
<?php
    foreach ($this->availableTickets as $ticket) {
?>
                        <option value="<?= xml($ticket->serial) ?>">
                            <?= xml(
                                sprintf(
                                    '%s (%s, %d/%d used)'
                                    , $ticket->description
                                    , format_currency($ticket->currencyCode, $ticket->price)
                                    , $ticket->carnetsUsed
                                    , $ticket->carnets
                                )
                            ); ?>
                        </option>
<?php
    }
?>
                    </select>
                    <details>
                        <summary>Create a new ticket</summary>
                        <div><label>description <input type="text" size="64" name="ticket uses[<?= xml($i) ?>][description]"/></label></div>
                        <div><label>currency <input type="text" placeholder="XXX" value="<?= xml($this->defaultCurrency) ?>" size="3" minlength="3" maxlength="3" name="ticket uses[<?= xml($i) ?>][currency]"/></label></div>
                        <div><label>price <input type="number" min="0" step="any" name="ticket uses[<?= xml($i) ?>][price]"/></label></div>
                        <div><label>carnets <input type="number" value="1" min="1" step="1" name="ticket uses[<?= xml($i) ?>][carnets]"/></label></div>
                    </details>
                </td>
            </tr>
            <tr>
                <th><label for="ticket_uses_<?= xml($i) ?>_new_carnet">new carnet?</label></th>
                <td>
                    <input type="checkbox" id="ticket_uses_<?= xml($i) ?>_new_carnet" name="ticket uses[<?= xml($i) ?>][new carnet]" checked="checked"/>
                </td>
            </tr>
            <tr>
                <th><label for="ticket_uses_<?= xml($i) ?>_expire">expire?</label></th>
                <td>
                    <input type="checkbox" id="ticket_uses_<?= xml($i) ?>_expire" name="ticket uses[<?= xml($i) ?>][expire]"/>
                </td>
            </tr>
            <tr>
                <th><label for="ticket_uses_<?= xml($i) ?>_cover from"><a title="split ticketing - leave blank if not split">cover from</a></label></th>
                <td>
                    <input type="number" min="0" step="0.01" id="ticket_uses_<?= xml($i) ?>_cover from" name="ticket uses[<?= xml($i) ?>][cover from]"/> km
                </td>
            </tr>
            <tr>
                <th><label for="ticket_uses_<?= xml($i) ?>_cover to"><a title="split ticketing - leave blank if not split">cover to</a></label></th>
                <td>
                    <input type="number" min="0" step="0.01" id="ticket_uses_<?= xml($i) ?>_cover to" name="ticket uses[<?= xml($i) ?>][cover to]"/> km
                </td>
            </tr>
        </table>
<?php
}
if ($i > 1) {
?>
        </details>
<?php
}
?>
        <table id="credential_table">
            <colgroup>
                <col class="header"/>
                <col class="content"/>
            </colgroup>
<?php
foreach (
    [
        'host' => [$this->defaultHost, 'text'],
        'port' => [$this->defaultPort, 'number'],
        'database' => [$this->defaultDatabase, 'text'],
        'user' => [NULL, 'text'],
        'password' => [NULL, 'password'],
    ]
    as $field => [$default, $type]) {
    $id = str_replace(' ', '_', $field);
?>
            <tr>
                <th><label for="<?= xml($id) ?>"><?= xml($field) ?></label></th>
                <td>
                    <input
                        id="<?= xml($id) ?>"
                        name="<?= xml($field) ?>"
                        type="<?= xml($type) ?>"
                        value="<?= xml($default) ?>"
                        required="required"
                    />
                </td>
            </tr>
<?php
}
?>
        </table>
        <input id="submit_button" type="submit" />
        <button id="push_button" type="button">Push into queue</button>
    </form>
</main>
