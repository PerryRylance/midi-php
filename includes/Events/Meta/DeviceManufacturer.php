<?php

namespace PerryRylance\Midi\Events\Meta;

enum DeviceManufacturer: int
{
    case SEQUENTIAL_CIRCUITS = 0x01;
    case BIG_BRIAR = 0x02;
    case OCTAVE_PLATEAU = 0x03;
    case MOOG = 0x04;
    case PASSPORT_DESIGNS = 0x05;
    case LEXICON = 0x06;
    case KURZWEIL = 0x07;
    case FENDER = 0x08;
    case GULBRANSEN = 0x09;
    case DELTA_LABS = 0x0A;
    case SOUND_COMP = 0x0B;
    case GENERAL_ELECTRO = 0x0C;
    case TECHMAR = 0x0D;
    case MATTHEWS_RESEARCH = 0x0E;
    case OBERHEIM = 0x10;
    case PAIA = 0x11;
    case SIMMONS = 0x12;
    case GENTLE_ELECTRIC = 0x13;
    case FAIRLIGHT = 0x14;
    case JL_COOPER = 0x15;
    case LOWERY = 0x16;
    case LIN = 0x17;
    case EMU = 0x18;
    case PEAVEY = 0x1B;
    case BON_TEMPI = 0x20;
    case SIEL = 0x21;
    case SYNTHEAXE = 0x23;
    case HOHNER = 0x24;
    case CRUMAR = 0x25;
    case SOLTON = 0x26;
    case JELLINGHOUS_MS = 0x27;
    case CTS = 0x28;
    case PPG = 0x29;
    case ELKA = 0x2F;
    case KAWAI = 0x40;
    case ROLAND = 0x41;
    case KORG = 0x42;
    case YAMAHA = 0x43;
    case CASIO = 0x44;
    case AKAI = 0x45;
}
