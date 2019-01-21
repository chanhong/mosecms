// replaces special characters with HTML entities
SpawEditor.prototype.convertToEntities = function (src_string)
{
    var result = src_string;

    var entities = {
        // Latin-1
        "¡": "&iexcl;",
        "¢": "&cent;",
        "£": "&pound;",
        "¤": "&curren;",
        "¥": "&yen;",
        "¦": "&brvbar;",
        "§": "&sect;",
        "¨": "&uml;",
        "©": "&copy;",
        "ª": "&ordf;",
        "«": "&laquo;",
        "¬": "&not;",
        "­": "&shy;",
        "®": "&reg;",
        "¯": "&macr;",
        "°": "&deg;",
        "±": "&plusmn;",
        "²": "&sup2;",
        "³": "&sup3;",
        "´": "&acute;",
        "µ": "&micro;",
        "¶": "&para;",
        "·": "&middot;",
        "¸": "&cedil;",
        "¹": "&sup1;",
        "º": "&ordm;",
        "»": "&raquo;",
        "¼": "&frac14;",
        "½": "&frac12;",
        "¾": "&frac34;",
        "¿": "&iquest;",
        "À": "&Agrave;",
        "Á": "&Aacute;",
        "Â": "&Acirc;",
        "Ã": "&Atilde;",
        "Ä": "&Auml;",
        "Å": "&Aring;",
        "Æ": "&AElig;",
        "Ç": "&Ccedil;",
        "È": "&Egrave;",
        "É": "&Eacute;",
        "Ê": "&Ecirc;",
        "Ë": "&Euml;",
        "Ì": "&Igrave;",
        "Í": "&Iacute;",
        "Î": "&Icirc;",
        "Ï": "&Iuml;",
        "Ð": "&ETH;",
        "Ñ": "&Ntilde;",
        "Ò": "&Ograve;",
        "Ó": "&Oacute;",
        "Ô": "&Ocirc;",
        "Õ": "&Otilde;",
        "Ö": "&Ouml;",
        "×": "&times;",
        "Ø": "&Oslash;",
        "Ù": "&Ugrave;",
        "Ú": "&Uacute;",
        "Û": "&Ucirc;",
        "Ü": "&Uuml;",
        "Ý": "&Yacute;",
        "Þ": "&THORN;",
        "ß": "&szlig;",
        "à": "&agrave;",
        "á": "&aacute;",
        "â": "&acirc;",
        "ã": "&atilde;",
        "ä": "&auml;",
        "å": "&aring;",
        "æ": "&aelig;",
        "ç": "&ccedil;",
        "è": "&egrave;",
        "é": "&eacute;",
        "ê": "&ecirc;",
        "ë": "&euml;",
        "ì": "&igrave;",
        "í": "&iacute;",
        "î": "&icirc;",
        "ï": "&iuml;",
        "ð": "&eth;",
        "ñ": "&ntilde;",
        "ò": "&ograve;",
        "ó": "&oacute;",
        "ô": "&ocirc;",
        "õ": "&otilde;",
        "ö": "&ouml;",
        "÷": "&divide;",
        "ø": "&oslash;",
        "ù": "&ugrave;",
        "ú": "&uacute;",
        "û": "&ucirc;",
        "ü": "&uuml;",
        "ý": "&yacute;",
        "þ": "&thorn;",
        "ÿ": "&yuml;",
        // symbols and greek
        "ƒ": "&fnof;",
        "Α": "&Alpha;",
        "Β": "&Beta;",
        "γ": "&Gamma;",
        "Δ": "&Delta;",
        "Ε": "&Epsilon;",
        "Ζ": "&Zeta;",
        "Η": "&Eta;",
        "Θ": "&Theta;",
        "Ι": "&Iota;",
        "Κ": "&Kappa;",
        "Λ": "&Lambda;",
        "Μ": "&Mu;",
        "Ν": "&Nu;",
        "Ξ": "&Xi;",
        "Ο": "&Omicron;",
        "Π": "&Pi;",
        "Ρ": "&Rho;",
        "Σ": "&Sigma;",
        "Τ": "&Tau;",
        "Υ": "&Upsilon;",
        "Φ": "&Phi;",
        "Χ": "&Chi;",
        "Ψ": "&Psi;",
        "Ω": "&Omega;",
        "α": "&alpha;",
        "β": "&beta;",
        "γ": "&gamma;",
        "δ": "&delta;",
        "ε": "&epsilon;",
        "ζ": "&zeta;",
        "η": "&eta;",
        "θ": "&theta;",
        "ι": "&iota;",
        "κ": "&kappa;",
        "λ": "&lambda;",
        "μ": "&mu;",
        "ν": "&nu;",
        "ξ": "&xi;",
        "ο": "&omicron;",
        "π": "&pi;",
        "ρ": "&rho;",
        "ς": "&sigmaf;",
        "σ": "&sigma;",
        "τ": "&tau;",
        "υ": "&upsilon;",
        "φ": "&phi;",
        "χ": "&chi;",
        "ψ": "&psi;",
        "ω": "&omega;",
        "•": "&bull;",
        "…": "&hellip;",
        "′": "&prime;",
        "″": "&Prime;",
        "‾": "&oline;",
        "⁄": "&frasl;",
        "℘": "&weierp;",
        "ℑ": "&image;",
        "ℜ": "&real;",
        "™": "&trade;",
        "ℵ": "&alefsym;",
        "←": "&larr;",
        "↑": "&uarr;",
        "→": "&rarr;",
        "↓": "&darr;",
        "↔": "&harr;",
        "↵": "&crarr;",
        "⇐": "&lArr;",
        "⇑": "&uArr;",
        "⇒": "&rArr;",
        "⇔": "&hArr;",
        "∀": "&forall;",
        "∂": "&part;",
        "∃": "&exist;",
        "∅": "&empty;",
        "∇": "&nabla;",
        "∈": "&isin;",
        "∉": "&notin;",
        "∋": "&ni;",
        "∏": "&prod;",
        "∑": "&sum;",
        "−": "&minus;",
        "∗": "&lowast;",
        "√": "&radic;",
        "∝": "&prop;",
        "∞": "&infin;",
        "∧": "&and;",
        "∨": "&or;",
        "∩": "&cap;",
        "∪": "&cup;",
        "∫": "&int;",
        "≅": "&cong;",
        "≈": "&asymp;",
        "≠": "&ne;",
        "≡": "&equiv;",
        "≤": "&le;",
        "≥": "&ge;",
        "⊂": "&sub;",
        "⊃": "&sup;",
        "⊄": "&nsub;",
        "⊆": "&sube;",
        "⊇": "&supe;",
        "⊕": "&oplus;",
        "⊗": "&otimes;",
        "⊥": "&perp;",
        "⋅": "&sdot;",
        "⌈": "&lceil;",
        "⌉": "&rceil;",
        "⌊": "&lfloor;",
        "⌋": "&rfloor;",
        "〈": "&lang;",
        "〉": "&rang;",
        "◊": "&loz;",
        "♠": "&spades;",
        "♣": "&clubs;",
        "♥": "&hearts;",
        "♦": "&diams;",
        // special chars
        "Œ": "&OElig;",
        "œ": "&oelig;",
        "Š": "&Scaron;",
        "š": "&scaron;",
        "Ÿ": "&Yuml;",
        "ˆ": "&circ;",
        "˜": "&tilde;",
        " ": "&ensp;",
        " ": "&emsp;",
        " ": "&thinsp;",
        "‌": "&zwnj;",
        "‍": "&zwj;",
        "‎": "&lrm;",
        "‏": "&rlm;",
        "–": "&ndash;",
        "—": "&mdash;",
        "‘": "&lsquo;",
        "’": "&rsquo;",
        "‚": "&sbquo;",
        "„": "&bdquo;",
        "†": "&dagger;",
        "‡": "&Dagger;",
        "‰": "&permil;",
        "‹": "&lsaquo;",
        "›": "&rsaquo;",
        "€": "&euro;",
        "“": "&ldquo;",
        "”": "&rdquo;"
    }
    var entities_str = "¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿƒΑΒγΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩαβγδεζηθικλμνξοπρςστυφχψω•…′″‾⁄℘ℑℜ™ℵ←↑→↓↔↵⇐⇑⇒⇔∀∂∃∅∇∈∉∋∏∑−∗√∝∞∧∨∩∪∫≅≈≠≡≤≥⊂⊃⊄⊆⊇⊕⊗⊥⋅⌈⌉⌊⌋〈〉◊♠♣♥♦ŒœŠšŸˆ˜   ‌‍‎‏–—‘’‚“”„†‡‰‹›€";

    var rgx = new RegExp("[" + entities_str + "]", "gm");

    var matches = result.match(rgx);
    if (matches != null)
    {
        var processed = new Array();

        for (var i = 0; i < matches.length; i++)
        {
            if (processed[matches[i]] == null && entities[matches[i]] != null && entities[matches[i]] != undefined)
            {
                // register that the symbol was processed
                processed[matches[i]] = entities[matches[i]];
                var replace_rgx = new RegExp(matches[i], "gm");
                result = result.replace(replace_rgx, entities[matches[i]]);
            }
        }
    }

    return result;
}