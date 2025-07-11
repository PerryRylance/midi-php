<?php

namespace PerryRylance\Midi\Attributes;

enum Type : string
{
    case BYTE = "byte";
    case SHORT = "short";
    case INT = "int";
    case MIXED = "mixed";
}
