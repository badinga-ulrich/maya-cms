<?php
/**
 * List of LIBXML error codes, as extracted from the official docs.
 *
 * @package Application Utils
 * @subpackage XMLHelper
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 *
 * @see http://www.xmlsoft.org/html/libxml-xmlerror.html
 */

declare(strict_types=1);

namespace AppUtils;

class XMLHelper_LibXML
{
    public const OK = 0;
    public const INTERNAL_ERROR = 1;
    public const NO_MEMORY = 2;
    public const DOCUMENT_START = 3;
    public const DOCUMENT_EMPTY = 4;
    public const DOCUMENT_END = 5;
    public const INVALID_HEX_CHARREF = 6;
    public const INVALID_DEC_CHARREF = 7;
    public const INVALID_CHARREF = 8;
    public const INVALID_CHAR = 9;
    public const CHARREF_AT_EOF = 10;
    public const CHARREF_IN_PROLOG = 11;
    public const CHARREF_IN_EPILOG = 12;
    public const CHARREF_IN_DTD = 13;
    public const ENTITYREF_AT_EOF = 14;
    public const ENTITYREF_IN_PROLOG = 15;
    public const ENTITYREF_IN_EPILOG = 16;
    public const ENTITYREF_IN_DTD = 17;
    public const PEREF_AT_EOF = 18;
    public const PEREF_IN_PROLOG = 19;
    public const PEREF_IN_EPILOG = 20;
    public const PEREF_IN_INT_SUBSET = 21;
    public const ENTITYREF_NO_NAME = 22;
    public const ENTITYREF_SEMICOL_MISSING = 23;
    public const PEREF_NO_NAME = 24;
    public const PEREF_SEMICOL_MISSING = 25;
    public const UNDECLARED_ENTITY = 26;
    public const XML_WAR_UNDECLARED_ENTITY = 27;
    public const UNPARSED_ENTITY = 28;
    public const ENTITY_IS_EXTERNAL = 29;
    public const ENTITY_IS_PARAMETER = 30;
    public const UNKNOWN_ENCODING = 31;
    public const UNSUPPORTED_ENCODING = 32;
    public const STRING_NOT_STARTED = 33;
    public const STRING_NOT_CLOSED = 34;
    public const NS_DECL_ERROR = 35;
    public const ENTITY_NOT_STARTED = 36;
    public const ENTITY_NOT_FINISHED = 37;
    public const LT_IN_ATTRIBUTE = 38;
    public const ATTRIBUTE_NOT_STARTED = 39;
    public const ATTRIBUTE_NOT_FINISHED = 40;
    public const ATTRIBUTE_WITHOUT_VALUE = 41;
    public const ATTRIBUTE_REDEFINED = 42;
    public const LITERAL_NOT_STARTED = 43;
    public const LITERAL_NOT_FINISHED = 44;
    public const COMMENT_NOT_FINISHED = 45;
    public const PI_NOT_STARTED = 46;
    public const PI_NOT_FINISHED = 47;
    public const NOTATION_NOT_STARTED = 48;
    public const NOTATION_NOT_FINISHED = 49;
    public const ATTLIST_NOT_STARTED = 50;
    public const ATTLIST_NOT_FINISHED = 51;
    public const MIXED_NOT_STARTED = 52;
    public const MIXED_NOT_FINISHED = 53;
    public const ELEMCONTENT_NOT_STARTED = 54;
    public const ELEMCONTENT_NOT_FINISHED = 55;
    public const XMLDECL_NOT_STARTED = 56;
    public const XMLDECL_NOT_FINISHED = 57;
    public const CONDSEC_NOT_STARTED = 58;
    public const CONDSEC_NOT_FINISHED = 59;
    public const EXT_SUBSET_NOT_FINISHED = 60;
    public const DOCTYPE_NOT_FINISHED = 61;
    public const MISPLACED_CDATA_END = 62;
    public const CDATA_NOT_FINISHED = 63;
    public const RESERVED_XML_NAME = 64;
    public const SPACE_REQUIRED = 65;
    public const SEPARATOR_REQUIRED = 66;
    public const NMTOKEN_REQUIRED = 67;
    public const NAME_REQUIRED = 68;
    public const PCDATA_REQUIRED = 69;
    public const URI_REQUIRED = 70;
    public const PUBID_REQUIRED = 71;
    public const LT_REQUIRED = 72;
    public const GT_REQUIRED = 73;
    public const LTSLASH_REQUIRED = 74;
    public const EQUAL_REQUIRED = 75;
    public const TAG_NAME_MISMATCH = 76;
    public const TAG_NOT_FINISHED = 77;
    public const STANDALONE_VALUE = 78;
    public const ENCODING_NAME = 79;
    public const HYPHEN_IN_COMMENT = 80;
    public const INVALID_ENCODING = 81;
    public const EXT_ENTITY_STANDALONE = 82;
    public const CONDSEC_INVALID = 83;
    public const VALUE_REQUIRED = 84;
    public const NOT_WELL_BALANCED = 85;
    public const EXTRA_CONTENT = 86;
    public const ENTITY_CHAR_ERROR = 87;
    public const ENTITY_PE_INTERNAL = 88;
    public const ENTITY_LOOP = 89;
    public const ENTITY_BOUNDARY = 90;
    public const INVALID_URI = 91;
    public const URI_FRAGMENT = 92;
    public const XML_WAR_CATALOG_PI = 93;
    public const NO_DTD = 94;
    public const CONDSEC_INVALID_KEYWORD = 95;
    public const VERSION_MISSING = 96;
    public const XML_WAR_UNKNOWN_VERSION = 97;
    public const XML_WAR_LANG_VALUE = 98;
    public const XML_WAR_NS_URI = 99;
    public const XML_WAR_NS_URI_RELATIVE = 100;
    public const MISSING_ENCODING = 101;
    public const XML_WAR_SPACE_VALUE = 102;
    public const NOT_STANDALONE = 103;
    public const ENTITY_PROCESSING = 104;
    public const NOTATION_PROCESSING = 105;
    public const XML_WAR_NS_COLUMN = 106;
    public const XML_WAR_ENTITY_REDEFINED = 107;
    public const UNKNOWN_VERSION = 108;
    public const VERSION_MISMATCH = 109;
    public const NAME_TOO_LONG = 110;
    public const USER_STOP = 111;
    public const XML_NS_ERR_XML_NAMESPACE = 200;
    public const XML_NS_ERR_UNDEFINED_NAMESPACE = 201;
    public const XML_NS_ERR_QNAME = 202;
    public const XML_NS_ERR_ATTRIBUTE_REDEFINED = 203;
    public const XML_NS_ERR_EMPTY = 204;
    public const XML_NS_ERR_COLON = 205;
    public const XML_DTD_ATTRIBUTE_DEFAULT = 500;
    public const XML_DTD_ATTRIBUTE_REDEFINED = 501;
    public const XML_DTD_ATTRIBUTE_VALUE = 502;
    public const XML_DTD_CONTENT_ERROR = 503;
    public const XML_DTD_CONTENT_MODEL = 504;
    public const XML_DTD_CONTENT_NOT_DETERMINIST = 505;
    public const XML_DTD_DIFFERENT_PREFIX = 506;
    public const XML_DTD_ELEM_DEFAULT_NAMESPACE = 507;
    public const XML_DTD_ELEM_NAMESPACE = 508;
    public const XML_DTD_ELEM_REDEFINED = 509;
    public const XML_DTD_EMPTY_NOTATION = 510;
    public const XML_DTD_ENTITY_TYPE = 511;
    public const XML_DTD_ID_FIXED = 512;
    public const XML_DTD_ID_REDEFINED = 513;
    public const XML_DTD_ID_SUBSET = 514;
    public const XML_DTD_INVALID_CHILD = 515;
    public const XML_DTD_INVALID_DEFAULT = 516;
    public const XML_DTD_LOAD_ERROR = 517;
    public const XML_DTD_MISSING_ATTRIBUTE = 518;
    public const XML_DTD_MIXED_CORRUPT = 519;
    public const XML_DTD_MULTIPLE_ID = 520;
    public const XML_DTD_NO_DOC = 521;
    public const XML_DTD_NO_DTD = 522;
    public const XML_DTD_NO_ELEM_NAME = 523;
    public const XML_DTD_NO_PREFIX = 524;
    public const XML_DTD_NO_ROOT = 525;
    public const XML_DTD_NOTATION_REDEFINED = 526;
    public const XML_DTD_NOTATION_VALUE = 527;
    public const XML_DTD_NOT_EMPTY = 528;
    public const XML_DTD_NOT_PCDATA = 529;
    public const XML_DTD_NOT_STANDALONE = 530;
    public const XML_DTD_ROOT_NAME = 531;
    public const XML_DTD_STANDALONE_WHITE_SPACE = 532;
    public const XML_DTD_UNKNOWN_ATTRIBUTE = 533;
    public const XML_DTD_UNKNOWN_ELEM = 534;
    public const XML_DTD_UNKNOWN_ENTITY = 535;
    public const XML_DTD_UNKNOWN_ID = 536;
    public const XML_DTD_UNKNOWN_NOTATION = 537;
    public const XML_DTD_STANDALONE_DEFAULTED = 538;
    public const XML_DTD_XMLID_VALUE = 539;
    public const XML_DTD_XMLID_TYPE = 540;
    public const XML_DTD_DUP_TOKEN = 541;
    public const XML_HTML_STRUCURE_ERROR = 800;
    public const XML_HTML_UNKNOWN_TAG = 801;
    public const XML_RNGP_ANYNAME_ATTR_ANCESTOR = 1000;
    public const XML_RNGP_ATTR_CONFLICT = 1001;
    public const XML_RNGP_ATTRIBUTE_CHILDREN = 1002;
    public const XML_RNGP_ATTRIBUTE_CONTENT = 1003;
    public const XML_RNGP_ATTRIBUTE_EMPTY = 1004;
    public const XML_RNGP_ATTRIBUTE_NOOP = 1005;
    public const XML_RNGP_CHOICE_CONTENT = 1006;
    public const XML_RNGP_CHOICE_EMPTY = 1007;
    public const XML_RNGP_CREATE_FAILURE = 1008;
    public const XML_RNGP_DATA_CONTENT = 1009;
    public const XML_RNGP_DEF_CHOICE_AND_INTERLEAVE = 1010;
    public const XML_RNGP_DEFINE_CREATE_FAILED = 1011;
    public const XML_RNGP_DEFINE_EMPTY = 1012;
    public const XML_RNGP_DEFINE_MISSING = 1013;
    public const XML_RNGP_DEFINE_NAME_MISSING = 1014;
    public const XML_RNGP_ELEM_CONTENT_EMPTY = 1015;
    public const XML_RNGP_ELEM_CONTENT_ERROR = 1016;
    public const XML_RNGP_ELEMENT_EMPTY = 1017;
    public const XML_RNGP_ELEMENT_CONTENT = 1018;
    public const XML_RNGP_ELEMENT_NAME = 1019;
    public const XML_RNGP_ELEMENT_NO_CONTENT = 1020;
    public const XML_RNGP_ELEM_TEXT_CONFLICT = 1021;
    public const XML_RNGP_EMPTY = 1022;
    public const XML_RNGP_EMPTY_CONSTRUCT = 1023;
    public const XML_RNGP_EMPTY_CONTENT = 1024;
    public const XML_RNGP_EMPTY_NOT_EMPTY = 1025;
    public const XML_RNGP_ERROR_TYPE_LIB = 1026;
    public const XML_RNGP_EXCEPT_EMPTY = 1027;
    public const XML_RNGP_EXCEPT_MISSING = 1028;
    public const XML_RNGP_EXCEPT_MULTIPLE = 1029;
    public const XML_RNGP_EXCEPT_NO_CONTENT = 1030;
    public const XML_RNGP_EXTERNALREF_EMTPY = 1031;
    public const XML_RNGP_EXTERNAL_REF_FAILURE = 1032;
    public const XML_RNGP_EXTERNALREF_RECURSE = 1033;
    public const XML_RNGP_FORBIDDEN_ATTRIBUTE = 1034;
    public const XML_RNGP_FOREIGN_ELEMENT = 1035;
    public const XML_RNGP_GRAMMAR_CONTENT = 1036;
    public const XML_RNGP_GRAMMAR_EMPTY = 1037;
    public const XML_RNGP_GRAMMAR_MISSING = 1038;
    public const XML_RNGP_GRAMMAR_NO_START = 1039;
    public const XML_RNGP_GROUP_ATTR_CONFLICT = 1040;
    public const XML_RNGP_HREF_ERROR = 1041;
    public const XML_RNGP_INCLUDE_EMPTY = 1042;
    public const XML_RNGP_INCLUDE_FAILURE = 1043;
    public const XML_RNGP_INCLUDE_RECURSE = 1044;
    public const XML_RNGP_INTERLEAVE_ADD = 1045;
    public const XML_RNGP_INTERLEAVE_CREATE_FAILED = 1046;
    public const XML_RNGP_INTERLEAVE_EMPTY = 1047;
    public const XML_RNGP_INTERLEAVE_NO_CONTENT = 1048;
    public const XML_RNGP_INVALID_DEFINE_NAME = 1049;
    public const XML_RNGP_INVALID_URI = 1050;
    public const XML_RNGP_INVALID_VALUE = 1051;
    public const XML_RNGP_MISSING_HREF = 1052;
    public const XML_RNGP_NAME_MISSING = 1053;
    public const XML_RNGP_NEED_COMBINE = 1054;
    public const XML_RNGP_NOTALLOWED_NOT_EMPTY = 1055;
    public const XML_RNGP_NSNAME_ATTR_ANCESTOR = 1056;
    public const XML_RNGP_NSNAME_NO_NS = 1057;
    public const XML_RNGP_PARAM_FORBIDDEN = 1058;
    public const XML_RNGP_PARAM_NAME_MISSING = 1059;
    public const XML_RNGP_PARENTREF_CREATE_FAILED = 1060;
    public const XML_RNGP_PARENTREF_NAME_INVALID = 1061;
    public const XML_RNGP_PARENTREF_NO_NAME = 1062;
    public const XML_RNGP_PARENTREF_NO_PARENT = 1063;
    public const XML_RNGP_PARENTREF_NOT_EMPTY = 1064;
    public const XML_RNGP_PARSE_ERROR = 1065;
    public const XML_RNGP_PAT_ANYNAME_EXCEPT_ANYNAME = 1066;
    public const XML_RNGP_PAT_ATTR_ATTR = 1067;
    public const XML_RNGP_PAT_ATTR_ELEM = 1068;
    public const XML_RNGP_PAT_DATA_EXCEPT_ATTR = 1069;
    public const XML_RNGP_PAT_DATA_EXCEPT_ELEM = 1070;
    public const XML_RNGP_PAT_DATA_EXCEPT_EMPTY = 1071;
    public const XML_RNGP_PAT_DATA_EXCEPT_GROUP = 1072;
    public const XML_RNGP_PAT_DATA_EXCEPT_INTERLEAVE = 1073;
    public const XML_RNGP_PAT_DATA_EXCEPT_LIST = 1074;
    public const XML_RNGP_PAT_DATA_EXCEPT_ONEMORE = 1075;
    public const XML_RNGP_PAT_DATA_EXCEPT_REF = 1076;
    public const XML_RNGP_PAT_DATA_EXCEPT_TEXT = 1077;
    public const XML_RNGP_PAT_LIST_ATTR = 1078;
    public const XML_RNGP_PAT_LIST_ELEM = 1079;
    public const XML_RNGP_PAT_LIST_INTERLEAVE = 1080;
    public const XML_RNGP_PAT_LIST_LIST = 1081;
    public const XML_RNGP_PAT_LIST_REF = 1082;
    public const XML_RNGP_PAT_LIST_TEXT = 1083;
    public const XML_RNGP_PAT_NSNAME_EXCEPT_ANYNAME = 1084;
    public const XML_RNGP_PAT_NSNAME_EXCEPT_NSNAME = 1085;
    public const XML_RNGP_PAT_ONEMORE_GROUP_ATTR = 1086;
    public const XML_RNGP_PAT_ONEMORE_INTERLEAVE_ATTR = 1087;
    public const XML_RNGP_PAT_START_ATTR = 1088;
    public const XML_RNGP_PAT_START_DATA = 1089;
    public const XML_RNGP_PAT_START_EMPTY = 1090;
    public const XML_RNGP_PAT_START_GROUP = 1091;
    public const XML_RNGP_PAT_START_INTERLEAVE = 1092;
    public const XML_RNGP_PAT_START_LIST = 1093;
    public const XML_RNGP_PAT_START_ONEMORE = 1094;
    public const XML_RNGP_PAT_START_TEXT = 1095;
    public const XML_RNGP_PAT_START_VALUE = 1096;
    public const XML_RNGP_PREFIX_UNDEFINED = 1097;
    public const XML_RNGP_REF_CREATE_FAILED = 1098;
    public const XML_RNGP_REF_CYCLE = 1099;
    public const XML_RNGP_REF_NAME_INVALID = 1100;
    public const XML_RNGP_REF_NO_DEF = 1101;
    public const XML_RNGP_REF_NO_NAME = 1102;
    public const XML_RNGP_REF_NOT_EMPTY = 1103;
    public const XML_RNGP_START_CHOICE_AND_INTERLEAVE = 1104;
    public const XML_RNGP_START_CONTENT = 1105;
    public const XML_RNGP_START_EMPTY = 1106;
    public const XML_RNGP_START_MISSING = 1107;
    public const XML_RNGP_TEXT_EXPECTED = 1108;
    public const XML_RNGP_TEXT_HAS_CHILD = 1109;
    public const XML_RNGP_TYPE_MISSING = 1110;
    public const XML_RNGP_TYPE_NOT_FOUND = 1111;
    public const XML_RNGP_TYPE_VALUE = 1112;
    public const XML_RNGP_UNKNOWN_ATTRIBUTE = 1113;
    public const XML_RNGP_UNKNOWN_COMBINE = 1114;
    public const XML_RNGP_UNKNOWN_CONSTRUCT = 1115;
    public const XML_RNGP_UNKNOWN_TYPE_LIB = 1116;
    public const XML_RNGP_URI_FRAGMENT = 1117;
    public const XML_RNGP_URI_NOT_ABSOLUTE = 1118;
    public const XML_RNGP_VALUE_EMPTY = 1119;
    public const XML_RNGP_VALUE_NO_CONTENT = 1120;
    public const XML_RNGP_XMLNS_NAME = 1121;
    public const XML_RNGP_XML_NS = 1122;
    public const XML_XPATH_EXPRESSION_OK = 1200;
    public const XML_XPATH_NUMBER_ERROR = 1201;
    public const XML_XPATH_UNFINISHED_LITERAL_ERROR = 1202;
    public const XML_XPATH_START_LITERAL_ERROR = 1203;
    public const XML_XPATH_VARIABLE_REF_ERROR = 1204;
    public const XML_XPATH_UNDEF_VARIABLE_ERROR = 1205;
    public const XML_XPATH_INVALID_PREDICATE_ERROR = 1206;
    public const XML_XPATH_EXPR_ERROR = 1207;
    public const XML_XPATH_UNCLOSED_ERROR = 1208;
    public const XML_XPATH_UNKNOWN_FUNC_ERROR = 1209;
    public const XML_XPATH_INVALID_OPERAND = 1210;
    public const XML_XPATH_INVALID_TYPE = 1211;
    public const XML_XPATH_INVALID_ARITY = 1212;
    public const XML_XPATH_INVALID_CTXT_SIZE = 1213;
    public const XML_XPATH_INVALID_CTXT_POSITION = 1214;
    public const XML_XPATH_MEMORY_ERROR = 1215;
    public const XML_XPTR_SYNTAX_ERROR = 1216;
    public const XML_XPTR_RESOURCE_ERROR = 1217;
    public const XML_XPTR_SUB_RESOURCE_ERROR = 1218;
    public const XML_XPATH_UNDEF_PREFIX_ERROR = 1219;
    public const XML_XPATH_ENCODING_ERROR = 1220;
    public const XML_XPATH_INVALID_CHAR_ERROR = 1221;
    public const XML_TREE_INVALID_HEX = 1300;
    public const XML_TREE_INVALID_DEC = 1301;
    public const XML_TREE_UNTERMINATED_ENTITY = 1302;
    public const XML_TREE_NOT_UTF8 = 1303;
    public const XML_SAVE_NOT_UTF8 = 1400;
    public const XML_SAVE_CHAR_INVALID = 1401;
    public const XML_SAVE_NO_DOCTYPE = 1402;
    public const XML_SAVE_UNKNOWN_ENCODING = 1403;
    public const XML_REGEXP_COMPILE_ERROR = 1450;
    public const XML_IO_UNKNOWN = 1500;
    public const XML_IO_EACCES = 1501;
    public const XML_IO_EAGAIN = 1502;
    public const XML_IO_EBADF = 1503;
    public const XML_IO_EBADMSG = 1504;
    public const XML_IO_EBUSY = 1505;
    public const XML_IO_ECANCELED = 1506;
    public const XML_IO_ECHILD = 1507;
    public const XML_IO_EDEADLK = 1508;
    public const XML_IO_EDOM = 1509;
    public const XML_IO_EEXIST = 1510;
    public const XML_IO_EFAULT = 1511;
    public const XML_IO_EFBIG = 1512;
    public const XML_IO_EINPROGRESS = 1513;
    public const XML_IO_EINTR = 1514;
    public const XML_IO_EINVAL = 1515;
    public const XML_IO_EIO = 1516;
    public const XML_IO_EISDIR = 1517;
    public const XML_IO_EMFILE = 1518;
    public const XML_IO_EMLINK = 1519;
    public const XML_IO_EMSGSIZE = 1520;
    public const XML_IO_ENAMETOOLONG = 1521;
    public const XML_IO_ENFILE = 1522;
    public const XML_IO_ENODEV = 1523;
    public const XML_IO_ENOENT = 1524;
    public const XML_IO_ENOEXEC = 1525;
    public const XML_IO_ENOLCK = 1526;
    public const XML_IO_ENOMEM = 1527;
    public const XML_IO_ENOSPC = 1528;
    public const XML_IO_ENOSYS = 1529;
    public const XML_IO_ENOTDIR = 1530;
    public const XML_IO_ENOTEMPTY = 1531;
    public const XML_IO_ENOTSUP = 1532;
    public const XML_IO_ENOTTY = 1533;
    public const XML_IO_ENXIO = 1534;
    public const XML_IO_EPERM = 1535;
    public const XML_IO_EPIPE = 1536;
    public const XML_IO_ERANGE = 1537;
    public const XML_IO_EROFS = 1538;
    public const XML_IO_ESPIPE = 1539;
    public const XML_IO_ESRCH = 1540;
    public const XML_IO_ETIMEDOUT = 1541;
    public const XML_IO_EXDEV = 1542;
    public const XML_IO_NETWORK_ATTEMPT = 1543;
    public const XML_IO_ENCODER = 1544;
    public const XML_IO_FLUSH = 1545;
    public const XML_IO_WRITE = 1546;
    public const XML_IO_NO_INPUT = 1547;
    public const XML_IO_BUFFER_FULL = 1548;
    public const XML_IO_LOAD_ERROR = 1549;
    public const XML_IO_ENOTSOCK = 1550;
    public const XML_IO_EISCONN = 1551;
    public const XML_IO_ECONNREFUSED = 1552;
    public const XML_IO_ENETUNREACH = 1553;
    public const XML_IO_EADDRINUSE = 1554;
    public const XML_IO_EALREADY = 1555;
    public const XML_IO_EAFNOSUPPORT = 1556;
    public const XML_XINCLUDE_RECURSION = 1600;
    public const XML_XINCLUDE_PARSE_VALUE = 1601;
    public const XML_XINCLUDE_ENTITY_DEF_MISMATCH = 1602;
    public const XML_XINCLUDE_NO_HREF = 1603;
    public const XML_XINCLUDE_NO_FALLBACK = 1604;
    public const XML_XINCLUDE_HREF_URI = 1605;
    public const XML_XINCLUDE_TEXT_FRAGMENT = 1606;
    public const XML_XINCLUDE_TEXT_DOCUMENT = 1607;
    public const XML_XINCLUDE_INVALID_CHAR = 1608;
    public const XML_XINCLUDE_BUILD_FAILED = 1609;
    public const XML_XINCLUDE_UNKNOWN_ENCODING = 1610;
    public const XML_XINCLUDE_MULTIPLE_ROOT = 1611;
    public const XML_XINCLUDE_XPTR_FAILED = 1612;
    public const XML_XINCLUDE_XPTR_RESULT = 1613;
    public const XML_XINCLUDE_INCLUDE_IN_INCLUDE = 1614;
    public const XML_XINCLUDE_FALLBACKS_IN_INCLUDE = 1615;
    public const XML_XINCLUDE_FALLBACK_NOT_IN_INCLUDE = 1616;
    public const XML_XINCLUDE_DEPRECATED_NS = 1617;
    public const XML_XINCLUDE_FRAGMENT_ID = 1618;
    public const XML_CATALOG_MISSING_ATTR = 1650;
    public const XML_CATALOG_ENTRY_BROKEN = 1651;
    public const XML_CATALOG_PREFER_VALUE = 1652;
    public const XML_CATALOG_NOT_CATALOG = 1653;
    public const XML_CATALOG_RECURSION = 1654;
    public const XML_SCHEMAP_PREFIX_UNDEFINED = 1700;
    public const XML_SCHEMAP_ATTRFORMDEFAULT_VALUE = 1701;
    public const XML_SCHEMAP_ATTRGRP_NONAME_NOREF = 1702;
    public const XML_SCHEMAP_ATTR_NONAME_NOREF = 1703;
    public const XML_SCHEMAP_COMPLEXTYPE_NONAME_NOREF = 1704;
    public const XML_SCHEMAP_ELEMFORMDEFAULT_VALUE = 1705;
    public const XML_SCHEMAP_ELEM_NONAME_NOREF = 1706;
    public const XML_SCHEMAP_EXTENSION_NO_BASE = 1707;
    public const XML_SCHEMAP_FACET_NO_VALUE = 1708;
    public const XML_SCHEMAP_FAILED_BUILD_IMPORT = 1709;
    public const XML_SCHEMAP_GROUP_NONAME_NOREF = 1710;
    public const XML_SCHEMAP_IMPORT_NAMESPACE_NOT_URI = 1711;
    public const XML_SCHEMAP_IMPORT_REDEFINE_NSNAME = 1712;
    public const XML_SCHEMAP_IMPORT_SCHEMA_NOT_URI = 1713;
    public const XML_SCHEMAP_INVALID_BOOLEAN = 1714;
    public const XML_SCHEMAP_INVALID_ENUM = 1715;
    public const XML_SCHEMAP_INVALID_FACET = 1716;
    public const XML_SCHEMAP_INVALID_FACET_VALUE = 1717;
    public const XML_SCHEMAP_INVALID_MAXOCCURS = 1718;
    public const XML_SCHEMAP_INVALID_MINOCCURS = 1719;
    public const XML_SCHEMAP_INVALID_REF_AND_SUBTYPE = 1720;
    public const XML_SCHEMAP_INVALID_WHITE_SPACE = 1721;
    public const XML_SCHEMAP_NOATTR_NOREF = 1722;
    public const XML_SCHEMAP_NOTATION_NO_NAME = 1723;
    public const XML_SCHEMAP_NOTYPE_NOREF = 1724;
    public const XML_SCHEMAP_REF_AND_SUBTYPE = 1725;
    public const XML_SCHEMAP_RESTRICTION_NONAME_NOREF = 1726;
    public const XML_SCHEMAP_SIMPLETYPE_NONAME = 1727;
    public const XML_SCHEMAP_TYPE_AND_SUBTYPE = 1728;
    public const XML_SCHEMAP_UNKNOWN_ALL_CHILD = 1729;
    public const XML_SCHEMAP_UNKNOWN_ANYATTRIBUTE_CHILD = 1730;
    public const XML_SCHEMAP_UNKNOWN_ATTR_CHILD = 1731;
    public const XML_SCHEMAP_UNKNOWN_ATTRGRP_CHILD = 1732;
    public const XML_SCHEMAP_UNKNOWN_ATTRIBUTE_GROUP = 1733;
    public const XML_SCHEMAP_UNKNOWN_BASE_TYPE = 1734;
    public const XML_SCHEMAP_UNKNOWN_CHOICE_CHILD = 1735;
    public const XML_SCHEMAP_UNKNOWN_COMPLEXCONTENT_CHILD = 1736;
    public const XML_SCHEMAP_UNKNOWN_COMPLEXTYPE_CHILD = 1737;
    public const XML_SCHEMAP_UNKNOWN_ELEM_CHILD = 1738;
    public const XML_SCHEMAP_UNKNOWN_EXTENSION_CHILD = 1739;
    public const XML_SCHEMAP_UNKNOWN_FACET_CHILD = 1740;
    public const XML_SCHEMAP_UNKNOWN_FACET_TYPE = 1741;
    public const XML_SCHEMAP_UNKNOWN_GROUP_CHILD = 1742;
    public const XML_SCHEMAP_UNKNOWN_IMPORT_CHILD = 1743;
    public const XML_SCHEMAP_UNKNOWN_LIST_CHILD = 1744;
    public const XML_SCHEMAP_UNKNOWN_NOTATION_CHILD = 1745;
    public const XML_SCHEMAP_UNKNOWN_PROCESSCONTENT_CHILD = 1746;
    public const XML_SCHEMAP_UNKNOWN_REF = 1747;
    public const XML_SCHEMAP_UNKNOWN_RESTRICTION_CHILD = 1748;
    public const XML_SCHEMAP_UNKNOWN_SCHEMAS_CHILD = 1749;
    public const XML_SCHEMAP_UNKNOWN_SEQUENCE_CHILD = 1750;
    public const XML_SCHEMAP_UNKNOWN_SIMPLECONTENT_CHILD = 1751;
    public const XML_SCHEMAP_UNKNOWN_SIMPLETYPE_CHILD = 1752;
    public const XML_SCHEMAP_UNKNOWN_TYPE = 1753;
    public const XML_SCHEMAP_UNKNOWN_UNION_CHILD = 1754;
    public const XML_SCHEMAP_ELEM_DEFAULT_FIXED = 1755;
    public const XML_SCHEMAP_REGEXP_INVALID = 1756;
    public const XML_SCHEMAP_FAILED_LOAD = 1757;
    public const XML_SCHEMAP_NOTHING_TO_PARSE = 1758;
    public const XML_SCHEMAP_NOROOT = 1759;
    public const XML_SCHEMAP_REDEFINED_GROUP = 1760;
    public const XML_SCHEMAP_REDEFINED_TYPE = 1761;
    public const XML_SCHEMAP_REDEFINED_ELEMENT = 1762;
    public const XML_SCHEMAP_REDEFINED_ATTRGROUP = 1763;
    public const XML_SCHEMAP_REDEFINED_ATTR = 1764;
    public const XML_SCHEMAP_REDEFINED_NOTATION = 1765;
    public const XML_SCHEMAP_FAILED_PARSE = 1766;
    public const XML_SCHEMAP_UNKNOWN_PREFIX = 1767;
    public const XML_SCHEMAP_DEF_AND_PREFIX = 1768;
    public const XML_SCHEMAP_UNKNOWN_INCLUDE_CHILD = 1769;
    public const XML_SCHEMAP_INCLUDE_SCHEMA_NOT_URI = 1770;
    public const XML_SCHEMAP_INCLUDE_SCHEMA_NO_URI = 1771;
    public const XML_SCHEMAP_NOT_SCHEMA = 1772;
    public const XML_SCHEMAP_UNKNOWN_MEMBER_TYPE = 1773;
    public const XML_SCHEMAP_INVALID_ATTR_USE = 1774;
    public const XML_SCHEMAP_RECURSIVE = 1775;
    public const XML_SCHEMAP_SUPERNUMEROUS_LIST_ITEM_TYPE = 1776;
    public const XML_SCHEMAP_INVALID_ATTR_COMBINATION = 1777;
    public const XML_SCHEMAP_INVALID_ATTR_INLINE_COMBINATION = 1778;
    public const XML_SCHEMAP_MISSING_SIMPLETYPE_CHILD = 1779;
    public const XML_SCHEMAP_INVALID_ATTR_NAME = 1780;
    public const XML_SCHEMAP_REF_AND_CONTENT = 1781;
    public const XML_SCHEMAP_CT_PROPS_CORRECT_1 = 1782;
    public const XML_SCHEMAP_CT_PROPS_CORRECT_2 = 1783;
    public const XML_SCHEMAP_CT_PROPS_CORRECT_3 = 1784;
    public const XML_SCHEMAP_CT_PROPS_CORRECT_4 = 1785;
    public const XML_SCHEMAP_CT_PROPS_CORRECT_5 = 1786;
    public const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_1 = 1787;
    public const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_2_1_1 = 1788;
    public const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_2_1_2 = 1789;
    public const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_2_2 = 1790;
    public const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_3 = 1791;
    public const XML_SCHEMAP_WILDCARD_INVALID_NS_MEMBER = 1792;
    public const XML_SCHEMAP_INTERSECTION_NOT_EXPRESSIBLE = 1793;
    public const XML_SCHEMAP_UNION_NOT_EXPRESSIBLE = 1794;
    public const XML_SCHEMAP_SRC_IMPORT_3_1 = 1795;
    public const XML_SCHEMAP_SRC_IMPORT_3_2 = 1796;
    public const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_4_1 = 1797;
    public const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_4_2 = 1798;
    public const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_4_3 = 1799;
    public const XML_SCHEMAP_COS_CT_EXTENDS_1_3 = 1800;
    public const XML_SCHEMAV_NOROOT = 1801;
    public const XML_SCHEMAV_UNDECLAREDELEM = 1802;
    public const XML_SCHEMAV_NOTTOPLEVEL = 1803;
    public const XML_SCHEMAV_MISSING = 1804;
    public const XML_SCHEMAV_WRONGELEM = 1805;
    public const XML_SCHEMAV_NOTYPE = 1806;
    public const XML_SCHEMAV_NOROLLBACK = 1807;
    public const XML_SCHEMAV_ISABSTRACT = 1808;
    public const XML_SCHEMAV_NOTEMPTY = 1809;
    public const XML_SCHEMAV_ELEMCONT = 1810;
    public const XML_SCHEMAV_HAVEDEFAULT = 1811;
    public const XML_SCHEMAV_NOTNILLABLE = 1812;
    public const XML_SCHEMAV_EXTRACONTENT = 1813;
    public const XML_SCHEMAV_INVALIDATTR = 1814;
    public const XML_SCHEMAV_INVALIDELEM = 1815;
    public const XML_SCHEMAV_NOTDETERMINIST = 1816;
    public const XML_SCHEMAV_CONSTRUCT = 1817;
    public const XML_SCHEMAV_INTERNAL = 1818;
    public const XML_SCHEMAV_NOTSIMPLE = 1819;
    public const XML_SCHEMAV_ATTRUNKNOWN = 1820;
    public const XML_SCHEMAV_ATTRINVALID = 1821;
    public const XML_SCHEMAV_VALUE = 1822;
    public const XML_SCHEMAV_FACET = 1823;
    public const XML_SCHEMAV_CVC_DATATYPE_VALID_1_2_1 = 1824;
    public const XML_SCHEMAV_CVC_DATATYPE_VALID_1_2_2 = 1825;
    public const XML_SCHEMAV_CVC_DATATYPE_VALID_1_2_3 = 1826;
    public const XML_SCHEMAV_CVC_TYPE_3_1_1 = 1827;
    public const XML_SCHEMAV_CVC_TYPE_3_1_2 = 1828;
    public const XML_SCHEMAV_CVC_FACET_VALID = 1829;
    public const XML_SCHEMAV_CVC_LENGTH_VALID = 1830;
    public const XML_SCHEMAV_CVC_MINLENGTH_VALID = 1831;
    public const XML_SCHEMAV_CVC_MAXLENGTH_VALID = 1832;
    public const XML_SCHEMAV_CVC_MININCLUSIVE_VALID = 1833;
    public const XML_SCHEMAV_CVC_MAXINCLUSIVE_VALID = 1834;
    public const XML_SCHEMAV_CVC_MINEXCLUSIVE_VALID = 1835;
    public const XML_SCHEMAV_CVC_MAXEXCLUSIVE_VALID = 1836;
    public const XML_SCHEMAV_CVC_TOTALDIGITS_VALID = 1837;
    public const XML_SCHEMAV_CVC_FRACTIONDIGITS_VALID = 1838;
    public const XML_SCHEMAV_CVC_PATTERN_VALID = 1839;
    public const XML_SCHEMAV_CVC_ENUMERATION_VALID = 1840;
    public const XML_SCHEMAV_CVC_COMPLEX_TYPE_2_1 = 1841;
    public const XML_SCHEMAV_CVC_COMPLEX_TYPE_2_2 = 1842;
    public const XML_SCHEMAV_CVC_COMPLEX_TYPE_2_3 = 1843;
    public const XML_SCHEMAV_CVC_COMPLEX_TYPE_2_4 = 1844;
    public const XML_SCHEMAV_CVC_ELT_1 = 1845;
    public const XML_SCHEMAV_CVC_ELT_2 = 1846;
    public const XML_SCHEMAV_CVC_ELT_3_1 = 1847;
    public const XML_SCHEMAV_CVC_ELT_3_2_1 = 1848;
    public const XML_SCHEMAV_CVC_ELT_3_2_2 = 1849;
    public const XML_SCHEMAV_CVC_ELT_4_1 = 1850;
    public const XML_SCHEMAV_CVC_ELT_4_2 = 1851;
    public const XML_SCHEMAV_CVC_ELT_4_3 = 1852;
    public const XML_SCHEMAV_CVC_ELT_5_1_1 = 1853;
    public const XML_SCHEMAV_CVC_ELT_5_1_2 = 1854;
    public const XML_SCHEMAV_CVC_ELT_5_2_1 = 1855;
    public const XML_SCHEMAV_CVC_ELT_5_2_2_1 = 1856;
    public const XML_SCHEMAV_CVC_ELT_5_2_2_2_1 = 1857;
    public const XML_SCHEMAV_CVC_ELT_5_2_2_2_2 = 1858;
    public const XML_SCHEMAV_CVC_ELT_6 = 1859;
    public const XML_SCHEMAV_CVC_ELT_7 = 1860;
    public const XML_SCHEMAV_CVC_ATTRIBUTE_1 = 1861;
    public const XML_SCHEMAV_CVC_ATTRIBUTE_2 = 1862;
    public const XML_SCHEMAV_CVC_ATTRIBUTE_3 = 1863;
    public const XML_SCHEMAV_CVC_ATTRIBUTE_4 = 1864;
    public const XML_SCHEMAV_CVC_COMPLEX_TYPE_3_1 = 1865;
    public const XML_SCHEMAV_CVC_COMPLEX_TYPE_3_2_1 = 1866;
    public const XML_SCHEMAV_CVC_COMPLEX_TYPE_3_2_2 = 1867;
    public const XML_SCHEMAV_CVC_COMPLEX_TYPE_4 = 1868;
    public const XML_SCHEMAV_CVC_COMPLEX_TYPE_5_1 = 1869;
    public const XML_SCHEMAV_CVC_COMPLEX_TYPE_5_2 = 1870;
    public const XML_SCHEMAV_ELEMENT_CONTENT = 1871;
    public const XML_SCHEMAV_DOCUMENT_ELEMENT_MISSING = 1872;
    public const XML_SCHEMAV_CVC_COMPLEX_TYPE_1 = 1873;
    public const XML_SCHEMAV_CVC_AU = 1874;
    public const XML_SCHEMAV_CVC_TYPE_1 = 1875;
    public const XML_SCHEMAV_CVC_TYPE_2 = 1876;
    public const XML_SCHEMAV_CVC_IDC = 1877;
    public const XML_SCHEMAV_CVC_WILDCARD = 1878;
    public const XML_SCHEMAV_MISC = 1879;
    public const XML_XPTR_UNKNOWN_SCHEME = 1900;
    public const XML_XPTR_CHILDSEQ_START = 1901;
    public const XML_XPTR_EVAL_FAILED = 1902;
    public const XML_XPTR_EXTRA_OBJECTS = 1903;
    public const XML_C14N_CREATE_CTXT = 1950;
    public const XML_C14N_REQUIRES_UTF8 = 1951;
    public const XML_C14N_CREATE_STACK = 1952;
    public const XML_C14N_INVALID_NODE = 1953;
    public const XML_C14N_UNKNOW_NODE = 1954;
    public const XML_C14N_RELATIVE_NAMESPACE = 1955;
    public const XML_FTP_PASV_ANSWER = 2000;
    public const XML_FTP_EPSV_ANSWER = 2001;
    public const XML_FTP_ACCNT = 2002;
    public const XML_FTP_URL_SYNTAX = 2003;
    public const XML_HTTP_URL_SYNTAX = 2020;
    public const XML_HTTP_USE_IP = 2021;
    public const XML_HTTP_UNKNOWN_HOST = 2022;
    public const XML_SCHEMAP_SRC_SIMPLE_TYPE_1 = 3000;
    public const XML_SCHEMAP_SRC_SIMPLE_TYPE_2 = 3001;
    public const XML_SCHEMAP_SRC_SIMPLE_TYPE_3 = 3002;
    public const XML_SCHEMAP_SRC_SIMPLE_TYPE_4 = 3003;
    public const XML_SCHEMAP_SRC_RESOLVE = 3004;
    public const XML_SCHEMAP_SRC_RESTRICTION_BASE_OR_SIMPLETYPE = 3005;
    public const XML_SCHEMAP_SRC_LIST_ITEMTYPE_OR_SIMPLETYPE = 3006;
    public const XML_SCHEMAP_SRC_UNION_MEMBERTYPES_OR_SIMPLETYPES = 3007;
    public const XML_SCHEMAP_ST_PROPS_CORRECT_1 = 3008;
    public const XML_SCHEMAP_ST_PROPS_CORRECT_2 = 3009;
    public const XML_SCHEMAP_ST_PROPS_CORRECT_3 = 3010;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_1_1 = 3011;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_1_2 = 3012;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_1_3_1 = 3013;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_1_3_2 = 3014;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_2_1 = 3015;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_2_3_1_1 = 3016;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_2_3_1_2 = 3017;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_2_3_2_1 = 3018;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_2_3_2_2 = 3019;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_2_3_2_3 = 3020;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_2_3_2_4 = 3021;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_2_3_2_5 = 3022;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_3_1 = 3023;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_3_3_1 = 3024;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_3_3_1_2 = 3025;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_3_3_2_2 = 3026;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_3_3_2_1 = 3027;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_3_3_2_3 = 3028;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_3_3_2_4 = 3029;
    public const XML_SCHEMAP_COS_ST_RESTRICTS_3_3_2_5 = 3030;
    public const XML_SCHEMAP_COS_ST_DERIVED_OK_2_1 = 3031;
    public const XML_SCHEMAP_COS_ST_DERIVED_OK_2_2 = 3032;
    public const XML_SCHEMAP_S4S_ELEM_NOT_ALLOWED = 3033;
    public const XML_SCHEMAP_S4S_ELEM_MISSING = 3034;
    public const XML_SCHEMAP_S4S_ATTR_NOT_ALLOWED = 3035;
    public const XML_SCHEMAP_S4S_ATTR_MISSING = 3036;
    public const XML_SCHEMAP_S4S_ATTR_INVALID_VALUE = 3037;
    public const XML_SCHEMAP_SRC_ELEMENT_1 = 3038;
    public const XML_SCHEMAP_SRC_ELEMENT_2_1 = 3039;
    public const XML_SCHEMAP_SRC_ELEMENT_2_2 = 3040;
    public const XML_SCHEMAP_SRC_ELEMENT_3 = 3041;
    public const XML_SCHEMAP_P_PROPS_CORRECT_1 = 3042;
    public const XML_SCHEMAP_P_PROPS_CORRECT_2_1 = 3043;
    public const XML_SCHEMAP_P_PROPS_CORRECT_2_2 = 3044;
    public const XML_SCHEMAP_E_PROPS_CORRECT_2 = 3045;
    public const XML_SCHEMAP_E_PROPS_CORRECT_3 = 3046;
    public const XML_SCHEMAP_E_PROPS_CORRECT_4 = 3047;
    public const XML_SCHEMAP_E_PROPS_CORRECT_5 = 3048;
    public const XML_SCHEMAP_E_PROPS_CORRECT_6 = 3049;
    public const XML_SCHEMAP_SRC_INCLUDE = 3050;
    public const XML_SCHEMAP_SRC_ATTRIBUTE_1 = 3051;
    public const XML_SCHEMAP_SRC_ATTRIBUTE_2 = 3052;
    public const XML_SCHEMAP_SRC_ATTRIBUTE_3_1 = 3053;
    public const XML_SCHEMAP_SRC_ATTRIBUTE_3_2 = 3054;
    public const XML_SCHEMAP_SRC_ATTRIBUTE_4 = 3055;
    public const XML_SCHEMAP_NO_XMLNS = 3056;
    public const XML_SCHEMAP_NO_XSI = 3057;
    public const XML_SCHEMAP_COS_VALID_DEFAULT_1 = 3058;
    public const XML_SCHEMAP_COS_VALID_DEFAULT_2_1 = 3059;
    public const XML_SCHEMAP_COS_VALID_DEFAULT_2_2_1 = 3060;
    public const XML_SCHEMAP_COS_VALID_DEFAULT_2_2_2 = 3061;
    public const XML_SCHEMAP_CVC_SIMPLE_TYPE = 3062;
    public const XML_SCHEMAP_COS_CT_EXTENDS_1_1 = 3063;
    public const XML_SCHEMAP_SRC_IMPORT_1_1 = 3064;
    public const XML_SCHEMAP_SRC_IMPORT_1_2 = 3065;
    public const XML_SCHEMAP_SRC_IMPORT_2 = 3066;
    public const XML_SCHEMAP_SRC_IMPORT_2_1 = 3067;
    public const XML_SCHEMAP_SRC_IMPORT_2_2 = 3068;
    public const XML_SCHEMAP_INTERNAL = 3069;
    public const XML_SCHEMAP_NOT_DETERMINISTIC = 3070;
    public const XML_SCHEMAP_SRC_ATTRIBUTE_GROUP_1 = 3071;
    public const XML_SCHEMAP_SRC_ATTRIBUTE_GROUP_2 = 3072;
    public const XML_SCHEMAP_SRC_ATTRIBUTE_GROUP_3 = 3073;
    public const XML_SCHEMAP_MG_PROPS_CORRECT_1 = 3074;
    public const XML_SCHEMAP_MG_PROPS_CORRECT_2 = 3075;
    public const XML_SCHEMAP_SRC_CT_1 = 3076;
    public const XML_SCHEMAP_DERIVATION_OK_RESTRICTION_2_1_3 = 3077;
    public const XML_SCHEMAP_AU_PROPS_CORRECT_2 = 3078;
    public const XML_SCHEMAP_A_PROPS_CORRECT_2 = 3079;
    public const XML_SCHEMAP_C_PROPS_CORRECT = 3080;
    public const XML_SCHEMAP_SRC_REDEFINE = 3081;
    public const XML_SCHEMAP_SRC_IMPORT = 3082;
    public const XML_SCHEMAP_WARN_SKIP_SCHEMA = 3083;
    public const XML_SCHEMAP_WARN_UNLOCATED_SCHEMA = 3084;
    public const XML_SCHEMAP_WARN_ATTR_REDECL_PROH = 3085;
    public const XML_SCHEMAP_WARN_ATTR_POINTLESS_PROH = 3086;
    public const XML_SCHEMAP_AG_PROPS_CORRECT = 3087;
    public const XML_SCHEMAP_COS_CT_EXTENDS_1_2 = 3088;
    public const XML_SCHEMAP_AU_PROPS_CORRECT = 3089;
    public const XML_SCHEMAP_A_PROPS_CORRECT_3 = 3090;
    public const XML_SCHEMAP_COS_ALL_LIMITED = 3091;
    public const XML_SCHEMATRONV_ASSERT = 4000;
    public const XML_SCHEMATRONV_REPORT = 4001;
    public const XML_MODULE_OPEN = 4900;
    public const XML_MODULE_CLOSE = 4901;
    public const XML_CHECK_FOUND_ELEMENT = 5000;
    public const XML_CHECK_FOUND_ATTRIBUTE = 5001;
    public const XML_CHECK_FOUND_TEXT = 5002;
    public const XML_CHECK_FOUND_CDATA = 5003;
    public const XML_CHECK_FOUND_ENTITYREF = 5004;
    public const XML_CHECK_FOUND_ENTITY = 5005;
    public const XML_CHECK_FOUND_PI = 5006;
    public const XML_CHECK_FOUND_COMMENT = 5007;
    public const XML_CHECK_FOUND_DOCTYPE = 5008;
    public const XML_CHECK_FOUND_FRAGMENT = 5009;
    public const XML_CHECK_FOUND_NOTATION = 5010;
    public const XML_CHECK_UNKNOWN_NODE = 5011;
    public const XML_CHECK_ENTITY_TYPE = 5012;
    public const XML_CHECK_NO_PARENT = 5013;
    public const XML_CHECK_NO_DOC = 5014;
    public const XML_CHECK_NO_NAME = 5015;
    public const XML_CHECK_NO_ELEM = 5016;
    public const XML_CHECK_WRONG_DOC = 5017;
    public const XML_CHECK_NO_PREV = 5018;
    public const XML_CHECK_WRONG_PREV = 5019;
    public const XML_CHECK_NO_NEXT = 5020;
    public const XML_CHECK_WRONG_NEXT = 5021;
    public const XML_CHECK_NOT_DTD = 5022;
    public const XML_CHECK_NOT_ATTR = 5023;
    public const XML_CHECK_NOT_ATTR_DECL = 5024;
    public const XML_CHECK_NOT_ELEM_DECL = 5025;
    public const XML_CHECK_NOT_ENTITY_DECL = 5026;
    public const XML_CHECK_NOT_NS_DECL = 5027;
    public const XML_CHECK_NO_HREF = 5028;
    public const XML_CHECK_WRONG_PARENT = 5029;
    public const XML_CHECK_NS_SCOPE = 5030;
    public const XML_CHECK_NS_ANCESTOR = 5031;
    public const XML_CHECK_NOT_UTF8 = 5032;
    public const XML_CHECK_NO_DICT = 5033;
    public const XML_CHECK_NOT_NCNAME = 5034;
    public const XML_CHECK_OUTSIDE_DICT = 5035;
    public const XML_CHECK_WRONG_NAME = 5036;
    public const XML_CHECK_NAME_NOT_NULL = 5037;
    public const XML_I18N_NO_NAME = 6000;
    public const XML_I18N_NO_HANDLER = 6001;
    public const XML_I18N_EXCESS_HANDLER = 6002;
    public const XML_I18N_CONV_FAILED = 6003;
    public const XML_I18N_NO_OUTPUT = 6004;
    public const XML_BUF_OVERFLOW = 7000;
}

