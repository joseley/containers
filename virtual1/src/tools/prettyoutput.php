<?php

function prettyOutput(mixed $obj) : void {
    echo "<pre>";
    print_r($obj);
    echo "</pre>";
}

function buildHTMLTable(array $headers, array $body, string $class) : string {
    $htmlHeaders = buildHTMLTableHeaders($headers, "header");
    $htmlBody = buildHTMLTableBody($body, "body");

    return "<p><table class='$class'>$htmlHeaders$htmlBody</table></p>";
}

function buildHTMLTableHeaders(array $data) : string {
    $htmlHeaders = array_reduce($data, function($carry, $element) {
        return $carry .= "<th>$element</th>";
    }, "");

    return "<thead><tr>$htmlHeaders</tr><thead>";
}

function buildHTMLTableBody(array $data) : string {
    $htmlBody = array_reduce($data, function($carry, $element) {
        return $carry .= "<tr><td>" . implode("</td><td>", $element) . "</td></tr>";
    }, "");

    return "<tbody>$htmlBody</tbody>";
}
