<?php
// @codingStandardsIgnoreFile
/**
 * WordPress media types.
 *
 * @package blob-mimes
 * @since 0.1.0
 */

/**
 * Return MIME aliases for a particular file extension.
 *
 * @since 0.1.0
 *
 * @see {https://www.iana.org/assignments/media-types}
 * @see {https://raw.githubusercontent.com/apache/httpd/trunk/docs/conf/mime.types}
 * @see {http://hg.nginx.org/nginx/raw-file/default/conf/mime.types}
 * @see {https://cgit.freedesktop.org/xdg/shared-mime-info/plain/freedesktop.org.xml.in}
 * @see {https://raw.githubusercontent.com/apache/tika/master/tika-core/src/main/resources/org/apache/tika/mime/tika-mimetypes.xml}
 * @see {https://github.com/Blobfolio/blob-mimes}
 *
 * @param string $ext File extension.
 * @return array|bool MIME types. False on failure.
 */
function wp_get_mime_aliases( $ext = '' ) {
	$mimes = array(
		'32x' => array(
			'application/x-genesis-32x-rom',
		),
		'3dml' => array(
			'text/vnd.in3d.3dml',
		),
		'3ds' => array(
			'image/x-3ds',
		),
		'3fr' => array(
			'image/x-raw-hasselblad',
		),
		'3g2' => array(
			'audio/3gpp2',
			'video/3gpp2',
			'video/mp4',
		),
		'3ga' => array(
			'audio/3gpp',
			'audio/3gpp-encrypted',
			'audio/x-rn-3gpp-amr',
			'audio/x-rn-3gpp-amr-encrypted',
			'audio/x-rn-3gpp-amr-wb',
			'audio/x-rn-3gpp-amr-wb-encrypted',
			'video/3gp',
			'video/3gpp',
			'video/3gpp-encrypted',
			'video/mp4',
		),
		'3gp' => array(
			'audio/3gpp',
			'audio/3gpp-encrypted',
			'audio/x-rn-3gpp-amr',
			'audio/x-rn-3gpp-amr-encrypted',
			'audio/x-rn-3gpp-amr-wb',
			'audio/x-rn-3gpp-amr-wb-encrypted',
			'video/3gp',
			'video/3gpp',
			'video/3gpp-encrypted',
			'video/mp4',
		),
		'3gp2' => array(
			'audio/3gpp2',
			'video/3gpp2',
			'video/mp4',
		),
		'3gpp' => array(
			'audio/3gpp',
			'audio/3gpp-encrypted',
			'audio/x-rn-3gpp-amr',
			'audio/x-rn-3gpp-amr-encrypted',
			'audio/x-rn-3gpp-amr-wb',
			'audio/x-rn-3gpp-amr-wb-encrypted',
			'video/3gp',
			'video/3gpp',
			'video/3gpp-encrypted',
			'video/mp4',
		),
		'3gpp2' => array(
			'audio/3gpp2',
			'video/3gpp2',
			'video/mp4',
		),
		'4th' => array(
			'text/plain',
			'text/x-forth',
		),
		'7z' => array(
			'application/x-7z-compressed',
		),
		'a26' => array(
			'application/x-atari-2600-rom',
		),
		'a78' => array(
			'application/x-atari-7800-rom',
		),
		'aab' => array(
			'application/x-authorware-bin',
		),
		'aac' => array(
			'audio/aac',
			'audio/x-aac',
		),
		'aam' => array(
			'application/x-authorware-map',
		),
		'aart' => array(
			'text/plain',
		),
		'aas' => array(
			'application/x-authorware-seg',
		),
		'abs-linkmap' => array(
			'text/plain',
		),
		'abs-menulinks' => array(
			'text/plain',
		),
		'abw' => array(
			'application/x-abiword',
			'application/xml',
		),
		'ac' => array(
			'application/pkix-attr-cert',
			'text/plain',
		),
		'ac3' => array(
			'audio/ac3',
		),
		'acc' => array(
			'application/vnd.americandynamics.acc',
		),
		'ace' => array(
			'application/x-ace',
			'application/x-ace-compressed',
		),
		'acfm' => array(
			'application/x-font-adobe-metric',
		),
		'acu' => array(
			'application/vnd.acucobol',
		),
		'acutc' => array(
			'application/vnd.acucorp',
		),
		'ad' => array(
			'text/plain',
			'text/x-asciidoc',
		),
		'ada' => array(
			'text/plain',
			'text/x-ada',
		),
		'adb' => array(
			'text/plain',
			'text/x-ada',
			'text/x-adasrc',
		),
		'adf' => array(
			'application/x-amiga-disk-format',
		),
		'adoc' => array(
			'text/plain',
			'text/x-asciidoc',
		),
		'adp' => array(
			'audio/adpcm',
		),
		'ads' => array(
			'text/plain',
			'text/x-ada',
			'text/x-adasrc',
		),
		'aep' => array(
			'application/vnd.adobe.aftereffects.project',
			'application/vnd.audiograph',
		),
		'aet' => array(
			'application/vnd.adobe.aftereffects.template',
		),
		'afm' => array(
			'application/x-font-adobe-metric',
			'application/x-font-afm',
			'application/x-font-type1',
		),
		'afp' => array(
			'application/vnd.ibm.modcap',
		),
		'ag' => array(
			'image/x-applix-graphics',
		),
		'agb' => array(
			'application/x-gba-rom',
		),
		'ahead' => array(
			'application/vnd.ahead.space',
		),
		'ai' => array(
			'application/illustrator',
			'application/postscript',
		),
		'aif' => array(
			'application/x-iff',
			'audio/aiff',
			'audio/x-aiff',
		),
		'aifc' => array(
			'application/x-iff',
			'audio/aiff',
			'audio/x-aifc',
			'audio/x-aiff',
			'audio/x-aiffc',
		),
		'aiff' => array(
			'application/x-iff',
			'audio/aiff',
			'audio/x-aiff',
		),
		'aiffc' => array(
			'application/x-iff',
			'audio/x-aifc',
			'audio/x-aiffc',
		),
		'air' => array(
			'application/vnd.adobe.air-application-installer-package+zip',
		),
		'ait' => array(
			'application/vnd.dvb.ait',
		),
		'aj' => array(
			'text/plain',
			'text/x-aspectj',
		),
		'al' => array(
			'application/x-executable',
			'application/x-perl',
			'text/plain',
			'text/x-perl',
		),
		'alz' => array(
			'application/x-alz',
		),
		'am' => array(
			'text/plain',
		),
		'amfm' => array(
			'application/x-font-adobe-metric',
		),
		'ami' => array(
			'application/vnd.amiga.ami',
		),
		'amr' => array(
			'audio/amr',
			'audio/amr-encrypted',
		),
		'amz' => array(
			'audio/x-amzxml',
		),
		'ani' => array(
			'application/x-navi-animation',
		),
		'anpa' => array(
			'text/vnd.iptc.anpa',
		),
		'anx' => array(
			'application/annodex',
			'application/x-annodex',
		),
		'any' => array(
			'application/vnd.mitsubishi.misty-guard.trustweb',
		),
		'ape' => array(
			'audio/x-ape',
		),
		'apk' => array(
			'application/java-archive',
			'application/vnd.android.package-archive',
			'application/x-java-archive',
		),
		'appcache' => array(
			'text/cache-manifest',
		),
		'appimage' => array(
			'application/x-executable',
			'application/x-iso9660-appimage',
		),
		'applescript' => array(
			'text/plain',
			'text/x-applescript',
		),
		'application' => array(
			'application/x-ms-application',
		),
		'apr' => array(
			'application/vnd.lotus-approach',
		),
		'apxml' => array(
			'application/auth-policy+xml',
		),
		'ar' => array(
			'application/x-archive',
			'application/x-unix-archive',
		),
		'arc' => array(
			'application/x-freearc',
		),
		'arj' => array(
			'application/x-arj',
			'application/x-arj-compressed',
		),
		'arw' => array(
			'image/x-dcraw',
			'image/x-raw-sony',
			'image/x-sony-arw',
		),
		'as' => array(
			'application/x-applix-spreadsheet',
			'text/plain',
			'text/x-actionscript',
		),
		'asc' => array(
			'application/pgp',
			'application/pgp-encrypted',
			'application/pgp-keys',
			'application/pgp-signature',
			'text/plain',
		),
		'ascii' => array(
			'text/vnd.ascii-art',
		),
		'asciidoc' => array(
			'text/plain',
			'text/x-asciidoc',
		),
		'asf' => array(
			'application/vnd.ms-asf',
			'video/x-ms-asf',
			'video/x-ms-asf-plugin',
			'video/x-ms-wm',
		),
		'asice' => array(
			'application/vnd.etsi.asic-e+zip',
			'application/zip',
		),
		'asics' => array(
			'application/vnd.etsi.asic-s+zip',
			'application/zip',
		),
		'asm' => array(
			'text/plain',
			'text/x-asm',
			'text/x-assembly',
		),
		'asnd' => array(
			'audio/vnd.adobe.soundbooth',
		),
		'aso' => array(
			'application/vnd.accpac.simply.aso',
		),
		'asp' => array(
			'application/x-asp',
			'text/asp',
			'text/plain',
		),
		'aspx' => array(
			'text/aspdotnet',
			'text/plain',
		),
		'ass' => array(
			'text/plain',
			'text/x-ssa',
		),
		'asx' => array(
			'application/x-ms-asx',
			'application/xml',
			'audio/x-ms-asx',
			'video/x-ms-asf',
			'video/x-ms-wax',
			'video/x-ms-wmx',
			'video/x-ms-wvx',
		),
		'atc' => array(
			'application/vnd.acucorp',
		),
		'atom' => array(
			'application/atom+xml',
			'application/xml',
		),
		'atomcat' => array(
			'application/atomcat+xml',
		),
		'atomdeleted' => array(
			'application/atomdeleted+xml',
		),
		'atomsvc' => array(
			'application/atomsvc+xml',
		),
		'atx' => array(
			'application/vnd.antix.game-component',
		),
		'au' => array(
			'audio/basic',
		),
		'auc' => array(
			'application/tamp-apex-update-confirm',
		),
		'automount' => array(
			'text/plain',
			'text/x-systemd-unit',
		),
		'avf' => array(
			'video/avi',
			'video/divx',
			'video/msvideo',
			'video/vnd.divx',
			'video/x-avi',
			'video/x-msvideo',
		),
		'avi' => array(
			'video/avi',
			'video/divx',
			'video/msvideo',
			'video/vnd.divx',
			'video/x-avi',
			'video/x-msvideo',
		),
		'aw' => array(
			'application/applixware',
			'application/x-applix-word',
		),
		'awb' => array(
			'audio/amr-wb',
			'audio/amr-wb-encrypted',
		),
		'awk' => array(
			'application/x-awk',
			'application/x-executable',
			'text/plain',
			'text/x-awk',
		),
		'axa' => array(
			'application/annodex',
			'audio/annodex',
			'audio/x-annodex',
		),
		'axv' => array(
			'application/annodex',
			'video/annodex',
			'video/x-annodex',
		),
		'axx' => array(
			'application/x-axcrypt',
		),
		'azf' => array(
			'application/vnd.airzip.filesecure.azf',
		),
		'azs' => array(
			'application/vnd.airzip.filesecure.azs',
		),
		'azw' => array(
			'application/vnd.amazon.ebook',
		),
		'azw3' => array(
			'application/vnd.amazon.mobi8-ebook',
		),
		'bak' => array(
			'application/x-trash',
		),
		'bas' => array(
			'text/plain',
			'text/x-basic',
		),
		'bash' => array(
			'application/x-sh',
			'text/plain',
		),
		'bat' => array(
			'application/x-msdownload',
		),
		'bay' => array(
			'image/x-raw-casio',
		),
		'bcpio' => array(
			'application/x-bcpio',
		),
		'bdf' => array(
			'application/x-font-bdf',
		),
		'bdm' => array(
			'application/vnd.syncml.dm+wbxml',
			'video/mp2t',
		),
		'bdmv' => array(
			'video/mp2t',
		),
		'bed' => array(
			'application/vnd.realvnc.bed',
		),
		'bh2' => array(
			'application/vnd.fujitsu.oasysprs',
		),
		'bib' => array(
			'application/x-bibtex-text-file',
			'text/plain',
			'text/x-bibtex',
		),
		'bibtex' => array(
			'application/x-bibtex-text-file',
			'text/plain',
		),
		'bin' => array(
			'application/octet-stream',
			'application/x-saturn-rom',
			'application/x-sega-cd-rom',
		),
		'blb' => array(
			'application/x-blorb',
		),
		'blend' => array(
			'application/x-blender',
		),
		'blender' => array(
			'application/x-blender',
		),
		'blorb' => array(
			'application/x-blorb',
		),
		'bmi' => array(
			'application/vnd.bmi',
		),
		'bmp' => array(
			'image/bmp',
			'image/x-bmp',
			'image/x-ms-bmp',
		),
		'book' => array(
			'application/vnd.framemaker',
		),
		'box' => array(
			'application/vnd.previewsystems.box',
		),
		'boz' => array(
			'application/x-bzip',
			'application/x-bzip2',
		),
		'bpg' => array(
			'image/x-bpg',
		),
		'bpk' => array(
			'application/octet-stream',
		),
		'bpm' => array(
			'application/bizagi-modeler',
			'application/zip',
		),
		'bsdiff' => array(
			'application/x-bsdiff',
		),
		'btf' => array(
			'image/prs.btif',
		),
		'btif' => array(
			'image/prs.btif',
		),
		'bz' => array(
			'application/x-bzip',
			'application/x-bzip2',
		),
		'bz2' => array(
			'application/x-bzip',
			'application/x-bzip2',
		),
		'c' => array(
			'text/x-c',
		),
		'c11amc' => array(
			'application/vnd.cluetrust.cartomobile-config',
		),
		'c11amz' => array(
			'application/vnd.cluetrust.cartomobile-config-pkg',
		),
		'c4d' => array(
			'application/vnd.clonk.c4group',
		),
		'c4f' => array(
			'application/vnd.clonk.c4group',
		),
		'c4g' => array(
			'application/vnd.clonk.c4group',
		),
		'c4p' => array(
			'application/vnd.clonk.c4group',
		),
		'c4u' => array(
			'application/vnd.clonk.c4group',
		),
		'cab' => array(
			'application/vnd.ms-cab-compressed',
			'zz-application/zz-winassoc-cab',
		),
		'cacerts' => array(
			'application/x-java-keystore',
		),
		'caf' => array(
			'audio/x-caf',
		),
		'cap' => array(
			'application/pcap',
			'application/vnd.tcpdump.pcap',
			'application/x-pcap',
		),
		'car' => array(
			'application/vnd.curl.car',
		),
		'cat' => array(
			'application/vnd.ms-pki.seccat',
		),
		'cb7' => array(
			'application/x-7z-compressed',
			'application/x-cb7',
			'application/x-cbr',
		),
		'cba' => array(
			'application/x-cbr',
		),
		'cbl' => array(
			'text/plain',
			'text/x-cobol',
		),
		'cbor' => array(
			'application/cbor',
			'application/cose',
			'application/cose-key',
			'application/cose-key-set',
		),
		'cbr' => array(
			'application/vnd.rar',
			'application/x-cbr',
		),
		'cbt' => array(
			'application/x-cbr',
			'application/x-cbt',
			'application/x-tar',
		),
		'cbz' => array(
			'application/vnd.comicbook+zip',
			'application/x-cbr',
			'application/x-cbz',
			'application/zip',
		),
		'cc' => array(
			'text/plain',
			'text/x-c',
			'text/x-c++src',
			'text/x-csrc',
		),
		'ccc' => array(
			'text/vnd.net2phone.commcenter.command',
		),
		'ccmp' => array(
			'application/ccmp+xml',
		),
		'ccmx' => array(
			'application/x-ccmx',
			'text/plain',
		),
		'cco' => array(
			'application/x-cocoa',
		),
		'cct' => array(
			'application/x-director',
		),
		'ccxml' => array(
			'application/ccxml+xml',
		),
		'cdbcmsg' => array(
			'application/vnd.contact.cmsg',
		),
		'cdf' => array(
			'application/x-netcdf',
		),
		'cdkey' => array(
			'application/vnd.mediastation.cdkey',
		),
		'cdmia' => array(
			'application/cdmi-capability',
		),
		'cdmic' => array(
			'application/cdmi-container',
		),
		'cdmid' => array(
			'application/cdmi-domain',
		),
		'cdmio' => array(
			'application/cdmi-object',
		),
		'cdmiq' => array(
			'application/cdmi-queue',
		),
		'cdr' => array(
			'application/cdr',
			'application/coreldraw',
			'application/vnd.corel-draw',
			'application/x-cdr',
			'application/x-coreldraw',
			'image/cdr',
			'image/x-cdr',
			'zz-application/zz-winassoc-cdr',
		),
		'cdx' => array(
			'chemical/x-cdx',
		),
		'cdxml' => array(
			'application/vnd.chemdraw+xml',
		),
		'cdy' => array(
			'application/vnd.cinderella',
		),
		'cer' => array(
			'application/pkix-cert',
		),
		'cert' => array(
			'application/x-x509-ca-cert',
		),
		'cfc' => array(
			'text/plain',
			'text/x-coldfusion',
		),
		'cfg' => array(
			'text/plain',
		),
		'cfm' => array(
			'text/plain',
			'text/x-coldfusion',
		),
		'cfml' => array(
			'text/plain',
			'text/x-coldfusion',
		),
		'cfs' => array(
			'application/x-cfs-compressed',
		),
		'cgb' => array(
			'application/x-gameboy-color-rom',
		),
		'cgi' => array(
			'text/plain',
			'text/x-cgi',
		),
		'cgm' => array(
			'image/cgm',
		),
		'chat' => array(
			'application/x-chat',
		),
		'chm' => array(
			'application/vnd.ms-htmlhelp',
			'application/x-chm',
		),
		'chrt' => array(
			'application/vnd.kde.kchart',
			'application/x-kchart',
		),
		'cif' => array(
			'chemical/x-cif',
		),
		'cii' => array(
			'application/vnd.anser-web-certificate-issue-initiation',
		),
		'cil' => array(
			'application/vnd.ms-artgalry',
		),
		'cl' => array(
			'application/simple-filter+xml',
			'message/imdn+xml',
			'text/plain',
			'text/x-common-lisp',
			'text/x-csrc',
			'text/x-opencl-src',
		),
		'cla' => array(
			'application/vnd.claymore',
		),
		'class' => array(
			'application/java',
			'application/java-byte-code',
			'application/java-vm',
			'application/x-java',
			'application/x-java-class',
			'application/x-java-vm',
		),
		'classpath' => array(
			'text/plain',
		),
		'clj' => array(
			'text/plain',
			'text/x-clojure',
		),
		'clkk' => array(
			'application/vnd.crick.clicker.keyboard',
		),
		'clkp' => array(
			'application/vnd.crick.clicker.palette',
		),
		'clkt' => array(
			'application/vnd.crick.clicker.template',
		),
		'clkw' => array(
			'application/vnd.crick.clicker.wordbank',
		),
		'clkx' => array(
			'application/vnd.crick.clicker',
		),
		'clp' => array(
			'application/x-msclip',
		),
		'clpi' => array(
			'video/mp2t',
		),
		'cls' => array(
			'application/x-tex',
			'text/plain',
			'text/x-basic',
			'text/x-tex',
			'text/x-vbasic',
		),
		'clue' => array(
			'application/clueinfo+xml',
		),
		'cmake' => array(
			'text/plain',
			'text/x-cmake',
		),
		'cmc' => array(
			'application/vnd.cosmocaller',
		),
		'cmd' => array(
			'text/plain',
		),
		'cmdf' => array(
			'chemical/x-cmdf',
		),
		'cml' => array(
			'chemical/x-cml',
		),
		'cmp' => array(
			'application/vnd.yellowriver-custom-menu',
		),
		'cmsc' => array(
			'application/cms',
		),
		'cmx' => array(
			'image/x-cmx',
		),
		'cnd' => array(
			'text/jcr-cnd',
		),
		'cob' => array(
			'text/plain',
			'text/x-cobol',
		),
		'cod' => array(
			'application/vnd.rim.cod',
		),
		'coffee' => array(
			'application/vnd.coffeescript',
			'text/plain',
			'text/x-coffeescript',
		),
		'com' => array(
			'application/x-msdownload',
		),
		'conf' => array(
			'text/plain',
		),
		'config' => array(
			'text/plain',
		),
		'core' => array(
			'application/x-core',
		),
		'cpi' => array(
			'video/mp2t',
		),
		'cpio' => array(
			'application/x-cpio',
		),
		'cpl' => array(
			'application/cpl+xml',
		),
		'cpp' => array(
			'text/plain',
			'text/x-c',
			'text/x-c++src',
			'text/x-csrc',
		),
		'cpt' => array(
			'application/mac-compactpro',
		),
		'cr2' => array(
			'image/x-canon-cr2',
			'image/x-dcraw',
			'image/x-raw-canon',
		),
		'crd' => array(
			'application/x-mscardfile',
		),
		'crdownload' => array(
			'application/x-partial-download',
		),
		'crl' => array(
			'application/pkix-crl',
		),
		'crt' => array(
			'application/x-x509-ca-cert',
		),
		'crw' => array(
			'image/x-canon-crw',
			'image/x-dcraw',
			'image/x-raw-canon',
		),
		'crx' => array(
			'application/x-chrome-package',
		),
		'cryptonote' => array(
			'application/vnd.rig.cryptonote',
		),
		'cs' => array(
			'text/plain',
			'text/x-csharp',
			'text/x-csrc',
		),
		'csh' => array(
			'application/x-csh',
			'application/x-shellscript',
		),
		'csml' => array(
			'chemical/x-csml',
		),
		'csp' => array(
			'application/vnd.commonspace',
		),
		'csrattrs' => array(
			'application/csrattrs',
		),
		'css' => array(
			'text/css',
			'text/plain',
		),
		'cst' => array(
			'application/x-director',
		),
		'csv' => array(
			'text/csv',
			'text/plain',
			'text/x-comma-separated-values',
			'text/x-csv',
		),
		'csvs' => array(
			'text/csv-schema',
			'text/plain',
		),
		'cu' => array(
			'application/cu-seeme',
		),
		'cuc' => array(
			'application/tamp-community-update-confirm',
		),
		'cue' => array(
			'application/x-cue',
			'text/plain',
		),
		'cur' => array(
			'image/x-win-bitmap',
		),
		'curl' => array(
			'text/vnd.curl',
		),
		'cw' => array(
			'application/prs.cww',
		),
		'cwiki' => array(
			'text/plain',
		),
		'cwk' => array(
			'application/x-appleworks',
		),
		'cww' => array(
			'application/prs.cww',
		),
		'cxt' => array(
			'application/x-director',
		),
		'cxx' => array(
			'text/plain',
			'text/x-c',
			'text/x-c++src',
			'text/x-csrc',
		),
		'dae' => array(
			'model/vnd.collada+xml',
		),
		'daf' => array(
			'application/vnd.mobius.daf',
		),
		'dar' => array(
			'application/x-dar',
		),
		'dart' => array(
			'application/vnd.dart',
		),
		'data' => array(
			'text/plain',
		),
		'dataless' => array(
			'application/vnd.fdsn.seed',
		),
		'davmount' => array(
			'application/davmount+xml',
		),
		'dbase' => array(
			'application/x-dbf',
		),
		'dbase3' => array(
			'application/x-dbf',
		),
		'dbf' => array(
			'application/dbase',
			'application/dbf',
			'application/x-dbase',
			'application/x-dbf',
		),
		'dbk' => array(
			'application/docbook+xml',
			'application/vnd.oasis.docbook+xml',
			'application/x-docbook+xml',
			'application/xml',
		),
		'dc' => array(
			'application/x-dc-rom',
		),
		'dcl' => array(
			'text/plain',
			'text/x-dcl',
		),
		'dcm' => array(
			'application/dicom',
		),
		'dcr' => array(
			'application/x-director',
			'image/x-dcraw',
			'image/x-kodak-dcr',
		),
		'dcs' => array(
			'image/x-raw-kodak',
		),
		'dcurl' => array(
			'text/vnd.curl.dcurl',
		),
		'dd2' => array(
			'application/vnd.oma.dd2+xml',
		),
		'ddd' => array(
			'application/vnd.fujixerox.ddd',
		),
		'ddf' => array(
			'application/vnd.syncml.dmddf+wbxml',
			'application/vnd.syncml.dmddf+xml',
		),
		'dds' => array(
			'image/x-dds',
		),
		'deb' => array(
			'application/octet-stream',
			'application/vnd.debian.binary-package',
			'application/x-archive',
			'application/x-deb',
			'application/x-debian-package',
		),
		'def' => array(
			'text/plain',
		),
		'deploy' => array(
			'application/octet-stream',
		),
		'der' => array(
			'application/x-x509-ca-cert',
		),
		'desktop' => array(
			'application/x-desktop',
			'application/x-gnome-app-info',
			'text/plain',
		),
		'device' => array(
			'text/plain',
			'text/x-systemd-unit',
		),
		'dex' => array(
			'application/x-dex',
		),
		'dfac' => array(
			'application/vnd.dreamfactory',
		),
		'dgc' => array(
			'application/x-dgc-compressed',
		),
		'di' => array(
			'text/x-csrc',
			'text/x-dsrc',
		),
		'dia' => array(
			'application/x-dia-diagram',
			'application/xml',
		),
		'dib' => array(
			'image/bmp',
			'image/x-bmp',
			'image/x-ms-bmp',
		),
		'dic' => array(
			'text/x-c',
		),
		'dicomdir' => array(
			'application/dicom',
		),
		'dif' => array(
			'application/dif+xml',
			'application/xml',
		),
		'diff' => array(
			'text/plain',
			'text/x-diff',
			'text/x-patch',
		),
		'dir' => array(
			'application/x-director',
		),
		'dis' => array(
			'application/vnd.mobius.dis',
		),
		'disposition-notification' => array(
			'message/disposition-notification',
		),
		'dist' => array(
			'application/octet-stream',
		),
		'distz' => array(
			'application/octet-stream',
		),
		'dita' => array(
			'application/dita+xml',
			'application/dita+xmlformattopic',
		),
		'ditamap' => array(
			'application/dita+xml',
			'application/dita+xmlformatmap',
		),
		'ditaval' => array(
			'application/dita+xml',
			'application/dita+xmlformatval',
		),
		'divx' => array(
			'video/avi',
			'video/divx',
			'video/msvideo',
			'video/vnd.divx',
			'video/x-avi',
			'video/x-msvideo',
		),
		'djv' => array(
			'image/vnd.djvu',
			'image/vnd.djvu+multipage',
			'image/x-djvu',
			'image/x.djvu',
		),
		'djvu' => array(
			'image/vnd.djvu',
			'image/vnd.djvu+multipage',
			'image/x-djvu',
			'image/x.djvu',
		),
		'dll' => array(
			'application/octet-stream',
			'application/x-msdownload',
		),
		'dmg' => array(
			'application/octet-stream',
			'application/x-apple-diskimage',
		),
		'dmp' => array(
			'application/pcap',
			'application/vnd.tcpdump.pcap',
			'application/x-pcap',
		),
		'dms' => array(
			'application/octet-stream',
		),
		'dna' => array(
			'application/vnd.dna',
		),
		'dng' => array(
			'image/x-adobe-dng',
			'image/x-dcraw',
			'image/x-raw-adobe',
		),
		'do' => array(
			'application/x-stata-do',
		),
		'doc' => array(
			'application/msword',
			'application/vnd.ms-office',
			'application/vnd.ms-word',
			'application/x-msword',
			'application/x-ole-storage',
			'application/xml',
			'zz-application/zz-winassoc-doc',
		),
		'docbook' => array(
			'application/docbook+xml',
			'application/vnd.oasis.docbook+xml',
			'application/x-docbook+xml',
			'application/xml',
		),
		'docm' => array(
			'application/vnd.ms-word.document.macroenabled.12',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		),
		'docx' => array(
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'application/zip',
		),
		'dot' => array(
			'application/msword',
			'application/msword-template',
			'application/vnd.ms-office',
			'application/vnd.ms-word',
			'application/xml',
			'text/vnd.graphviz',
		),
		'dotm' => array(
			'application/vnd.ms-word.template.macroenabled.12',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
		),
		'dotx' => array(
			'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
			'application/zip',
		),
		'dp' => array(
			'application/vnd.osgi.dp',
		),
		'dpg' => array(
			'application/vnd.dpgraph',
		),
		'dpr' => array(
			'text/plain',
			'text/x-pascal',
		),
		'dra' => array(
			'audio/vnd.dra',
		),
		'drc' => array(
			'video/ogg',
			'video/x-dirac',
		),
		'drf' => array(
			'image/x-raw-kodak',
		),
		'drle' => array(
			'image/dicom-rle',
		),
		'dsc' => array(
			'text/prs.lines.tag',
		),
		'dsl' => array(
			'text/plain',
			'text/x-dsl',
		),
		'dssc' => array(
			'application/dssc+der',
		),
		'dta' => array(
			'application/x-stata-dta',
		),
		'dtb' => array(
			'application/x-dtbook+xml',
		),
		'dtd' => array(
			'application/xml-dtd',
			'text/plain',
			'text/x-dtd',
		),
		'dts' => array(
			'audio/vnd.dts',
			'audio/x-dts',
		),
		'dtshd' => array(
			'audio/vnd.dts',
			'audio/vnd.dts.hd',
			'audio/x-dtshd',
		),
		'dtx' => array(
			'application/x-tex',
			'text/plain',
			'text/x-tex',
		),
		'dump' => array(
			'application/octet-stream',
		),
		'dv' => array(
			'video/dv',
		),
		'dvb' => array(
			'video/vnd.dvb.file',
		),
		'dvc' => array(
			'application/dvcs',
		),
		'dvi' => array(
			'application/x-dvi',
		),
		'dwf' => array(
			'drawing/x-dwf',
			'model/vnd.dwf',
		),
		'dwfx' => array(
			'model/vnd.dwfx+xps',
		),
		'dwg' => array(
			'application/acad',
			'application/autocaddwg',
			'application/dwg',
			'application/x-acad',
			'application/x-autocad',
			'application/x-dwg',
			'drawing/dwg',
			'image/vnd.dwg',
			'image/x-dwg',
		),
		'dxb' => array(
			'image/vnd.dxb',
		),
		'dxf' => array(
			'image/vnd.dxf',
		),
		'dxp' => array(
			'application/vnd.spotfire.dxp',
		),
		'dxr' => array(
			'application/x-director',
		),
		'ear' => array(
			'application/java-archive',
			'application/x-tika-java-enterprise-archive',
		),
		'ecelp4800' => array(
			'audio/vnd.nuera.ecelp4800',
		),
		'ecelp7470' => array(
			'audio/vnd.nuera.ecelp7470',
		),
		'ecelp9600' => array(
			'audio/vnd.nuera.ecelp9600',
		),
		'ecma' => array(
			'application/ecmascript',
		),
		'edm' => array(
			'application/vnd.novadigm.edm',
		),
		'edx' => array(
			'application/vnd.novadigm.edx',
		),
		'efif' => array(
			'application/vnd.picsel',
		),
		'egon' => array(
			'application/x-egon',
		),
		'egrm' => array(
			'text/plain',
		),
		'ei6' => array(
			'application/vnd.pg.osasli',
		),
		'eif' => array(
			'text/plain',
			'text/x-eiffel',
		),
		'el' => array(
			'text/plain',
			'text/x-emacs-lisp',
		),
		'elc' => array(
			'application/octet-stream',
			'application/x-elc',
		),
		'emf' => array(
			'application/emf',
			'application/x-emf',
			'application/x-msmetafile',
			'image/emf',
			'image/x-emf',
		),
		'eml' => array(
			'message/rfc822',
			'text/plain',
		),
		'emlx' => array(
			'message/x-emlx',
		),
		'emm' => array(
			'application/vnd.ibm.electronic-media',
		),
		'emma' => array(
			'application/emma+xml',
		),
		'emp' => array(
			'application/vnd.emusic-emusicpackage',
		),
		'emz' => array(
			'application/gzip',
			'application/gzip-compressed',
			'application/gzipped',
			'application/x-gunzip',
			'application/x-gzip',
			'application/x-gzip-compressed',
			'application/x-msmetafile',
			'gzip/document',
		),
		'enr' => array(
			'application/x-endnote-refer',
		),
		'ent' => array(
			'application/xml',
			'application/xml-external-parsed-entity',
			'text/plain',
			'text/xml-external-parsed-entity',
		),
		'enw' => array(
			'application/x-endnote-refer',
		),
		'eol' => array(
			'audio/vnd.digital-winds',
		),
		'eot' => array(
			'application/vnd.ms-fontobject',
		),
		'eps' => array(
			'application/postscript',
			'image/x-eps',
		),
		'epsf' => array(
			'application/postscript',
			'image/x-eps',
		),
		'epsi' => array(
			'application/postscript',
			'image/x-eps',
		),
		'epub' => array(
			'application/epub+zip',
			'application/zip',
		),
		'erf' => array(
			'image/x-raw-epson',
		),
		'erl' => array(
			'text/plain',
			'text/x-erlang',
		),
		'es' => array(
			'application/ecmascript',
			'application/x-executable',
			'text/ecmascript',
		),
		'es3' => array(
			'application/vnd.eszigno3+xml',
		),
		'esa' => array(
			'application/vnd.osgi.subsystem',
		),
		'esf' => array(
			'application/vnd.epson.esf',
		),
		'espass' => array(
			'application/vnd.espass-espass+zip',
		),
		'et3' => array(
			'application/vnd.eszigno3+xml',
		),
		'etheme' => array(
			'application/x-e-theme',
		),
		'etx' => array(
			'text/plain',
			'text/x-setext',
		),
		'eva' => array(
			'application/x-eva',
		),
		'evy' => array(
			'application/x-envoy',
		),
		'exe' => array(
			'application/octet-stream',
			'application/x-dosexec',
			'application/x-ms-dos-executable',
			'application/x-msdownload',
		),
		'exi' => array(
			'application/exi',
		),
		'exp' => array(
			'text/plain',
			'text/x-expect',
		),
		'exr' => array(
			'image/x-exr',
		),
		'ext' => array(
			'application/vnd.novadigm.ext',
		),
		'ez' => array(
			'application/andrew-inset',
		),
		'ez2' => array(
			'application/vnd.ezpix-album',
		),
		'ez3' => array(
			'application/vnd.ezpix-package',
		),
		'f' => array(
			'text/x-fortran',
		),
		'f4a' => array(
			'audio/m4a',
			'audio/mp4',
			'audio/x-m4a',
		),
		'f4b' => array(
			'audio/mp4',
			'audio/x-m4b',
		),
		'f4v' => array(
			'video/mp4',
			'video/mp4v-es',
			'video/x-f4v',
			'video/x-m4v',
		),
		'f77' => array(
			'text/plain',
			'text/x-fortran',
		),
		'f90' => array(
			'text/plain',
			'text/x-fortran',
		),
		'f95' => array(
			'text/plain',
			'text/x-fortran',
		),
		'fb2' => array(
			'application/x-fictionbook',
			'application/x-fictionbook+xml',
			'application/xml',
		),
		'fbs' => array(
			'image/vnd.fastbidsheet',
		),
		'fcdt' => array(
			'application/vnd.adobe.formscentral.fcdt',
		),
		'fcs' => array(
			'application/vnd.isac.fcs',
		),
		'fdf' => array(
			'application/vnd.fdf',
		),
		'fds' => array(
			'application/x-fds-disk',
		),
		'fe_launch' => array(
			'application/vnd.denovo.fcselayout-link',
		),
		'feature' => array(
			'text/plain',
			'text/x-gherkin',
		),
		'fff' => array(
			'image/x-raw-imacon',
		),
		'fg5' => array(
			'application/vnd.fujitsu.oasysgp',
		),
		'fgd' => array(
			'application/x-director',
		),
		'fh' => array(
			'image/x-freehand',
		),
		'fh10' => array(
			'image/x-freehand',
		),
		'fh11' => array(
			'image/x-freehand',
		),
		'fh12' => array(
			'image/x-freehand',
		),
		'fh4' => array(
			'image/x-freehand',
		),
		'fh40' => array(
			'image/x-freehand',
		),
		'fh5' => array(
			'image/x-freehand',
		),
		'fh50' => array(
			'image/x-freehand',
		),
		'fh7' => array(
			'image/x-freehand',
		),
		'fh8' => array(
			'image/x-freehand',
		),
		'fh9' => array(
			'image/x-freehand',
		),
		'fhc' => array(
			'image/x-freehand',
		),
		'fig' => array(
			'application/x-xfig',
			'image/x-xfig',
		),
		'fit' => array(
			'application/fits',
		),
		'fits' => array(
			'application/fits',
			'image/fits',
			'image/x-fits',
		),
		'fl' => array(
			'application/x-fluid',
			'text/plain',
		),
		'flac' => array(
			'audio/flac',
			'audio/x-flac',
		),
		'flatpak' => array(
			'application/vnd.flatpak',
			'application/vnd.xdgapp',
		),
		'flatpakref' => array(
			'application/vnd.flatpak.ref',
			'text/plain',
		),
		'flatpakrepo' => array(
			'application/vnd.flatpak.repo',
			'text/plain',
		),
		'flc' => array(
			'video/fli',
			'video/x-flc',
			'video/x-fli',
			'video/x-flic',
		),
		'fli' => array(
			'video/fli',
			'video/x-fli',
			'video/x-flic',
		),
		'flo' => array(
			'application/vnd.micrografx.flo',
		),
		'flv' => array(
			'application/x-flash-video',
			'flv-application/octet-stream',
			'video/flv',
			'video/x-flv',
		),
		'flw' => array(
			'application/vnd.kde.kivio',
			'application/x-kivio',
		),
		'flx' => array(
			'text/vnd.fmi.flexstor',
		),
		'fly' => array(
			'text/vnd.fly',
		),
		'fm' => array(
			'application/vnd.framemaker',
			'application/x-frame',
		),
		'fn' => array(
			'text/plain',
		),
		'fnc' => array(
			'application/vnd.frogans.fnc',
		),
		'fo' => array(
			'application/vnd.software602.filler.form+xml',
			'application/xml',
			'application/xslfo+xml',
			'text/x-xslfo',
			'text/xsl',
		),
		'fodg' => array(
			'application/vnd.oasis.opendocument.graphics-flat-xml',
			'application/xml',
		),
		'fodp' => array(
			'application/vnd.oasis.opendocument.presentation-flat-xml',
			'application/xml',
		),
		'fods' => array(
			'application/vnd.oasis.opendocument.spreadsheet-flat-xml',
			'application/xml',
		),
		'fodt' => array(
			'application/vnd.oasis.opendocument.text-flat-xml',
			'application/xml',
		),
		'for' => array(
			'text/plain',
			'text/x-fortran',
		),
		'fp7' => array(
			'application/x-filemaker',
		),
		'fpx' => array(
			'image/vnd.fpx',
		),
		'frame' => array(
			'application/vnd.framemaker',
		),
		'frm' => array(
			'text/x-basic',
			'text/x-vbasic',
		),
		'fsc' => array(
			'application/vnd.fsc.weblaunch',
		),
		'fst' => array(
			'image/vnd.fst',
		),
		'ft' => array(
			'text/plain',
		),
		'ft10' => array(
			'image/x-freehand',
		),
		'ft11' => array(
			'image/x-freehand',
		),
		'ft12' => array(
			'image/x-freehand',
		),
		'ft7' => array(
			'image/x-freehand',
		),
		'ft8' => array(
			'image/x-freehand',
		),
		'ft9' => array(
			'image/x-freehand',
		),
		'ftc' => array(
			'application/vnd.fluxtime.clip',
		),
		'fti' => array(
			'application/vnd.anser-web-funds-transfer-initiation',
		),
		'fts' => array(
			'application/fits',
		),
		'fv' => array(
			'text/plain',
		),
		'fvt' => array(
			'video/vnd.fvt',
		),
		'fxm' => array(
			'video/x-flv',
			'video/x-javafx',
		),
		'fxp' => array(
			'application/vnd.adobe.fxp',
		),
		'fxpl' => array(
			'application/vnd.adobe.fxp',
		),
		'fzs' => array(
			'application/vnd.fuzzysheet',
		),
		'g2w' => array(
			'application/vnd.geoplan',
		),
		'g3' => array(
			'image/fax-g3',
			'image/g3fax',
		),
		'g3w' => array(
			'application/vnd.geospace',
		),
		'gac' => array(
			'application/vnd.groove-account',
		),
		'gam' => array(
			'application/x-tads',
		),
		'gb' => array(
			'application/x-gameboy-rom',
		),
		'gba' => array(
			'application/x-gba-rom',
		),
		'gbc' => array(
			'application/x-gameboy-color-rom',
		),
		'gbr' => array(
			'application/rpki-ghostbusters',
		),
		'gca' => array(
			'application/x-gca-compressed',
		),
		'gcode' => array(
			'text/plain',
			'text/x.gcode',
		),
		'gcrd' => array(
			'text/directory',
			'text/plain',
			'text/vcard',
			'text/x-vcard',
		),
		'gdl' => array(
			'model/vnd.gdl',
		),
		'ged' => array(
			'application/x-gedcom',
			'text/gedcom',
		),
		'gedcom' => array(
			'application/x-gedcom',
			'text/gedcom',
		),
		'gem' => array(
			'application/x-gtar',
			'application/x-tar',
		),
		'gen' => array(
			'application/x-genesis-rom',
		),
		'generally' => array(
			'text/vnd.fmi.flexstor',
		),
		'geo' => array(
			'application/vnd.dynageo',
		),
		'geojson' => array(
			'application/geo+json',
			'application/json',
			'application/vnd.geo+json',
		),
		'gex' => array(
			'application/vnd.geometry-explorer',
		),
		'gf' => array(
			'application/x-tex-gf',
		),
		'gg' => array(
			'application/x-gamegear-rom',
		),
		'ggb' => array(
			'application/vnd.geogebra.file',
		),
		'ggt' => array(
			'application/vnd.geogebra.tool',
		),
		'ghf' => array(
			'application/vnd.groove-help',
		),
		'gif' => array(
			'image/gif',
		),
		'gim' => array(
			'application/vnd.groove-identity-message',
		),
		'glade' => array(
			'application/x-glade',
			'application/xml',
		),
		'gltf' => array(
			'model/gltf+json',
		),
		'gml' => array(
			'application/gml+xml',
			'application/xml',
		),
		'gmo' => array(
			'application/x-gettext-translation',
		),
		'gmx' => array(
			'application/vnd.gmx',
		),
		'gnc' => array(
			'application/x-gnucash',
		),
		'gnd' => array(
			'application/gnunet-directory',
		),
		'gnucash' => array(
			'application/x-gnucash',
		),
		'gnumeric' => array(
			'application/x-gnumeric',
			'application/x-gnumeric-spreadsheet',
		),
		'gnuplot' => array(
			'application/x-gnuplot',
			'text/plain',
		),
		'go' => array(
			'text/plain',
			'text/x-go',
		),
		'gp' => array(
			'application/x-gnuplot',
			'text/plain',
		),
		'gpg' => array(
			'application/pgp',
			'application/pgp-encrypted',
			'application/pgp-keys',
			'application/pgp-signature',
			'text/plain',
		),
		'gph' => array(
			'application/vnd.flographit',
		),
		'gplt' => array(
			'application/x-gnuplot',
			'text/plain',
		),
		'gpx' => array(
			'application/gpx',
			'application/gpx+xml',
			'application/x-gpx',
			'application/x-gpx+xml',
			'application/xml',
		),
		'gqf' => array(
			'application/vnd.grafeq',
		),
		'gqs' => array(
			'application/vnd.grafeq',
		),
		'gra' => array(
			'application/x-graphite',
		),
		'gram' => array(
			'application/srgs',
		),
		'gramps' => array(
			'application/x-gramps-xml',
		),
		'grb' => array(
			'application/x-grib',
		),
		'grb1' => array(
			'application/x-grib',
		),
		'grb2' => array(
			'application/x-grib',
		),
		'gre' => array(
			'application/vnd.geometry-explorer',
		),
		'grm' => array(
			'text/plain',
		),
		'groovy' => array(
			'text/plain',
			'text/x-groovy',
		),
		'grv' => array(
			'application/vnd.groove-injector',
		),
		'grxml' => array(
			'application/srgs+xml',
		),
		'gs' => array(
			'text/plain',
			'text/x-genie',
		),
		'gsf' => array(
			'application/postscript',
			'application/x-font-ghostscript',
			'application/x-font-type1',
		),
		'gsm' => array(
			'audio/x-gsm',
		),
		'gtar' => array(
			'application/x-gtar',
			'application/x-tar',
		),
		'gtm' => array(
			'application/vnd.groove-tool-message',
		),
		'gtw' => array(
			'model/vnd.gtw',
		),
		'gv' => array(
			'text/vnd.graphviz',
		),
		'gvp' => array(
			'text/google-video-pointer',
			'text/x-google-video-pointer',
		),
		'gxf' => array(
			'application/gxf',
		),
		'gxt' => array(
			'application/vnd.geonext',
		),
		'gz' => array(
			'application/gzip',
			'application/gzip-compressed',
			'application/gzipped',
			'application/x-gunzip',
			'application/x-gzip',
			'application/x-gzip-compressed',
			'gzip/document',
		),
		'h' => array(
			'text/x-c',
		),
		'h261' => array(
			'video/h261',
		),
		'h263' => array(
			'video/h263',
		),
		'h264' => array(
			'video/h264',
		),
		'h4' => array(
			'application/x-hdf',
		),
		'h5' => array(
			'application/x-hdf',
		),
		'hal' => array(
			'application/vnd.hal+xml',
		),
		'haml' => array(
			'text/plain',
			'text/x-haml',
		),
		'hbci' => array(
			'application/vnd.hbci',
		),
		'hdf' => array(
			'application/x-hdf',
		),
		'hdf4' => array(
			'application/x-hdf',
		),
		'hdf5' => array(
			'application/x-hdf',
		),
		'hdr' => array(
			'image/vnd.radiance',
		),
		'hdt' => array(
			'application/vnd.hdt',
		),
		'he5' => array(
			'application/x-hdf',
		),
		'heldxml' => array(
			'application/held+xml',
		),
		'hfa' => array(
			'application/x-erdas-hfa',
		),
		'hfe' => array(
			'application/x-hfe-floppy-image',
		),
		'hh' => array(
			'text/plain',
			'text/x-c',
			'text/x-c++hdr',
			'text/x-chdr',
		),
		'hlp' => array(
			'application/winhlp',
			'zz-application/zz-winassoc-hlp',
		),
		'hp' => array(
			'text/plain',
			'text/x-c++hdr',
			'text/x-chdr',
		),
		'hpgl' => array(
			'application/vnd.hp-hpgl',
		),
		'hpi' => array(
			'application/vnd.hp-hpid',
		),
		'hpid' => array(
			'application/vnd.hp-hpid',
		),
		'hpp' => array(
			'text/plain',
			'text/x-c++hdr',
			'text/x-chdr',
		),
		'hps' => array(
			'application/vnd.hp-hps',
		),
		'hpub' => array(
			'application/prs.hpub+zip',
		),
		'hqx' => array(
			'application/binhex',
			'application/mac-binhex',
			'application/mac-binhex40',
		),
		'hs' => array(
			'text/plain',
			'text/x-haskell',
		),
		'htaccess' => array(
			'text/plain',
		),
		'htc' => array(
			'text/x-component',
		),
		'htke' => array(
			'application/vnd.kenameaapp',
		),
		'htm' => array(
			'text/html',
			'text/plain',
		),
		'html' => array(
			'text/html',
			'text/plain',
		),
		'hvd' => array(
			'application/vnd.yamaha.hv-dic',
		),
		'hvp' => array(
			'application/vnd.yamaha.hv-voice',
		),
		'hvs' => array(
			'application/vnd.yamaha.hv-script',
		),
		'hwp' => array(
			'application/vnd.haansoft-hwp',
			'application/x-hwp',
		),
		'hwt' => array(
			'application/vnd.haansoft-hwt',
			'application/x-hwt',
		),
		'hx' => array(
			'text/plain',
			'text/x-haxe',
		),
		'hxx' => array(
			'text/plain',
			'text/x-c++hdr',
			'text/x-chdr',
		),
		'i2g' => array(
			'application/vnd.intergeo',
		),
		'i3' => array(
			'text/plain',
			'text/x-modula',
		),
		'ibooks' => array(
			'application/epub+zip',
			'application/x-ibooks+zip',
		),
		'ica' => array(
			'application/x-ica',
			'text/plain',
		),
		'icb' => array(
			'image/x-icb',
			'image/x-tga',
		),
		'icc' => array(
			'application/vnd.iccprofile',
		),
		'ice' => array(
			'x-conference/x-cooltalk',
		),
		'icm' => array(
			'application/vnd.iccprofile',
		),
		'icns' => array(
			'image/icns',
			'image/x-icns',
		),
		'ico' => array(
			'application/ico',
			'image/ico',
			'image/icon',
			'image/vnd.microsoft.icon',
			'image/x-ico',
			'image/x-icon',
			'text/ico',
		),
		'ics' => array(
			'application/ics',
			'text/calendar',
			'text/plain',
			'text/x-vcalendar',
		),
		'idl' => array(
			'text/plain',
			'text/x-idl',
		),
		'ief' => array(
			'image/ief',
		),
		'ifb' => array(
			'text/calendar',
			'text/plain',
		),
		'iff' => array(
			'application/x-iff',
			'image/x-iff',
			'image/x-ilbm',
		),
		'ifm' => array(
			'application/vnd.shana.informed.formdata',
		),
		'ig' => array(
			'text/plain',
			'text/x-modula',
		),
		'iges' => array(
			'model/iges',
			'text/plain',
		),
		'igl' => array(
			'application/vnd.igloader',
		),
		'igm' => array(
			'application/vnd.insors.igm',
		),
		'ign' => array(
			'application/vnd.coreos.ignition+json',
		),
		'ignition' => array(
			'application/vnd.coreos.ignition+json',
		),
		'igs' => array(
			'model/iges',
			'text/plain',
		),
		'igx' => array(
			'application/vnd.micrografx.igx',
		),
		'ihtml' => array(
			'text/plain',
		),
		'iif' => array(
			'application/vnd.shana.informed.interchange',
		),
		'iiq' => array(
			'image/x-raw-phaseone',
		),
		'ilbm' => array(
			'application/x-iff',
			'image/x-iff',
			'image/x-ilbm',
		),
		'ime' => array(
			'audio/imelody',
			'audio/x-imelody',
			'text/x-imelody',
		),
		'img' => array(
			'application/octet-stream',
			'application/x-raw-disk-image',
		),
		'imgcal' => array(
			'application/vnd.3lightssoftware.imagescal',
		),
		'imi' => array(
			'application/vnd.imagemeter.image+zip',
		),
		'imp' => array(
			'application/vnd.accpac.simply.imp',
		),
		'ims' => array(
			'application/vnd.ms-ims',
		),
		'imy' => array(
			'audio/imelody',
			'audio/x-imelody',
			'text/x-imelody',
		),
		'in' => array(
			'text/plain',
		),
		'indd' => array(
			'application/x-adobe-indesign',
		),
		'ini' => array(
			'text/plain',
			'text/x-ini',
		),
		'ink' => array(
			'application/inkml+xml',
		),
		'inkml' => array(
			'application/inkml+xml',
		),
		'ins' => array(
			'application/x-tex',
			'text/plain',
			'text/x-tex',
		),
		'install' => array(
			'application/x-install-instructions',
		),
		'inx' => array(
			'application/x-adobe-indesign-interchange',
			'application/xml',
		),
		'iota' => array(
			'application/vnd.astraea-software.iota',
		),
		'ipa' => array(
			'application/x-itunes-ipa',
			'application/zip',
		),
		'ipfix' => array(
			'application/ipfix',
		),
		'ipk' => array(
			'application/vnd.shana.informed.package',
		),
		'iptables' => array(
			'text/plain',
			'text/x-iptables',
		),
		'ipynb' => array(
			'application/json',
			'application/x-ipynb+json',
		),
		'irm' => array(
			'application/vnd.ibm.rights-management',
		),
		'irp' => array(
			'application/vnd.irepository.package+xml',
		),
		'iso' => array(
			'application/octet-stream',
			'application/x-cd-image',
			'application/x-gamecube-iso-image',
			'application/x-gamecube-rom',
			'application/x-iso9660-image',
			'application/x-raw-disk-image',
			'application/x-saturn-rom',
			'application/x-sega-cd-rom',
			'application/x-wbfs',
			'application/x-wia',
			'application/x-wii-iso-image',
			'application/x-wii-rom',
		),
		'iso19139' => array(
			'application/xml',
			'text/iso19139+xml',
		),
		'iso9660' => array(
			'application/x-cd-image',
			'application/x-iso9660-image',
			'application/x-raw-disk-image',
		),
		'it' => array(
			'audio/x-it',
		),
		'it87' => array(
			'application/x-it87',
			'text/plain',
		),
		'itk' => array(
			'application/x-tcl',
			'text/plain',
			'text/x-tcl',
		),
		'itp' => array(
			'application/vnd.shana.informed.formtemplate',
		),
		'ivp' => array(
			'application/vnd.immervision-ivp',
		),
		'ivu' => array(
			'application/vnd.immervision-ivu',
		),
		'j2c' => array(
			'image/x-jp2-codestream',
		),
		'jad' => array(
			'text/vnd.sun.j2me.app-descriptor',
		),
		'jam' => array(
			'application/vnd.jam',
		),
		'jar' => array(
			'application/java-archive',
			'application/x-jar',
			'application/x-java-archive',
			'application/zip',
		),
		'jardiff' => array(
			'application/x-java-archive-diff',
		),
		'java' => array(
			'text/plain',
			'text/x-csrc',
			'text/x-java',
			'text/x-java-source',
		),
		'jb2' => array(
			'image/x-jb2',
			'image/x-jbig2',
		),
		'jbig2' => array(
			'image/x-jb2',
			'image/x-jbig2',
		),
		'jceks' => array(
			'application/x-java-jce-keystore',
		),
		'jfi' => array(
			'image/jpeg',
		),
		'jfif' => array(
			'image/jpeg',
		),
		'jif' => array(
			'image/jpeg',
		),
		'jisp' => array(
			'application/vnd.jisp',
		),
		'jks' => array(
			'application/x-java-keystore',
		),
		'jl' => array(
			'text/plain',
			'text/x-common-lisp',
		),
		'jls' => array(
			'image/jls',
		),
		'jlt' => array(
			'application/vnd.hp-jlyt',
		),
		'jmx' => array(
			'text/plain',
		),
		'jng' => array(
			'image/x-jng',
			'video/x-jng',
		),
		'jnilib' => array(
			'application/x-java-jnilib',
		),
		'jnlp' => array(
			'application/x-java-jnlp-file',
			'application/xml',
		),
		'joda' => array(
			'application/vnd.joost.joda-archive',
		),
		'jp2' => array(
			'image/jp2',
			'image/jpeg2000',
			'image/jpeg2000-image',
			'image/jpx',
			'image/x-jp2-container',
			'image/x-jpeg2000-image',
		),
		'jpe' => array(
			'image/jpeg',
			'image/pjpeg',
		),
		'jpeg' => array(
			'image/jpeg',
			'image/pjpeg',
		),
		'jpf' => array(
			'image/jp2',
			'image/jpeg2000',
			'image/jpeg2000-image',
			'image/jpx',
			'image/x-jp2-container',
			'image/x-jpeg2000-image',
		),
		'jpg' => array(
			'image/jpeg',
			'image/pjpeg',
		),
		'jpgm' => array(
			'image/jpm',
			'image/x-jp2-container',
			'video/jpm',
		),
		'jpgv' => array(
			'video/jpeg',
		),
		'jpm' => array(
			'image/jpm',
			'image/x-jp2-container',
			'video/jpm',
		),
		'jpr' => array(
			'application/x-jbuilder-project',
		),
		'jpx' => array(
			'application/x-jbuilder-project',
			'image/jp2',
			'image/jpeg2000',
			'image/jpeg2000-image',
			'image/jpx',
			'image/x-jpeg2000-image',
		),
		'jrd' => array(
			'application/jrd+json',
			'application/json',
		),
		'js' => array(
			'application/ecmascript',
			'application/javascript',
			'application/x-javascript',
			'text/javascript',
			'text/plain',
		),
		'jsm' => array(
			'application/ecmascript',
			'application/javascript',
			'application/x-javascript',
			'text/javascript',
		),
		'json' => array(
			'application/dicom+json',
			'application/geo+json',
			'application/javascript',
			'application/json',
			'application/vnd.dataresource+json',
			'application/vnd.hc+json',
			'application/vnd.nearst.inv+json',
			'application/vnd.oftn.l10n+json',
			'application/vnd.tableschema+json',
			'application/vnd.vel+json',
		),
		'json-patch' => array(
			'application/json',
			'application/json-patch+json',
		),
		'jsonld' => array(
			'application/json',
			'application/ld+json',
		),
		'jsonml' => array(
			'application/jsonml+json',
		),
		'jsp' => array(
			'application/x-httpd-jsp',
			'text/plain',
			'text/x-jsp',
		),
		'junit' => array(
			'text/plain',
		),
		'jx' => array(
			'text/plain',
		),
		'k25' => array(
			'image/x-dcraw',
			'image/x-kodak-k25',
			'image/x-raw-kodak',
		),
		'k7' => array(
			'application/x-thomson-cassette',
		),
		'kar' => array(
			'audio/midi',
			'audio/x-midi',
		),
		'karbon' => array(
			'application/vnd.kde.karbon',
			'application/x-karbon',
		),
		'kdc' => array(
			'image/x-dcraw',
			'image/x-kodak-kdc',
			'image/x-raw-kodak',
		),
		'kdelnk' => array(
			'application/x-desktop',
			'application/x-gnome-app-info',
			'text/plain',
		),
		'kexi' => array(
			'application/x-kexiproject-sqlite',
			'application/x-kexiproject-sqlite2',
			'application/x-kexiproject-sqlite3',
			'application/x-sqlite2',
			'application/x-sqlite3',
			'application/x-vnd.kde.kexi',
		),
		'kexic' => array(
			'application/x-kexi-connectiondata',
		),
		'kexis' => array(
			'application/x-kexiproject-shortcut',
		),
		'key' => array(
			'application/vnd.apple.iwork',
			'application/vnd.apple.keynote',
			'application/x-iwork-keynote-sffkey',
			'application/zip',
		),
		'kfo' => array(
			'application/vnd.kde.kformula',
			'application/x-kformula',
		),
		'kia' => array(
			'application/vnd.kidspiration',
		),
		'kil' => array(
			'application/x-killustrator',
		),
		'kino' => array(
			'application/smil',
			'application/smil+xml',
			'application/xml',
		),
		'kml' => array(
			'application/vnd.google-earth.kml+xml',
			'application/xml',
		),
		'kmz' => array(
			'application/vnd.google-earth.kmz',
			'application/zip',
		),
		'kne' => array(
			'application/vnd.kinar',
		),
		'knp' => array(
			'application/vnd.kinar',
		),
		'kon' => array(
			'application/vnd.kde.kontour',
			'application/x-kontour',
		),
		'kpm' => array(
			'application/x-kpovmodeler',
		),
		'kpr' => array(
			'application/vnd.kde.kpresenter',
			'application/x-kpresenter',
		),
		'kpt' => array(
			'application/vnd.kde.kpresenter',
			'application/x-kpresenter',
		),
		'kpxx' => array(
			'application/vnd.ds-keypoint',
		),
		'kra' => array(
			'application/x-krita',
		),
		'ks' => array(
			'application/x-java-keystore',
		),
		'ksp' => array(
			'application/vnd.kde.kspread',
			'application/x-kspread',
		),
		'ktr' => array(
			'application/vnd.kahootz',
		),
		'ktx' => array(
			'image/ktx',
		),
		'ktz' => array(
			'application/vnd.kahootz',
		),
		'kud' => array(
			'application/x-kugar',
		),
		'kwd' => array(
			'application/vnd.kde.kword',
			'application/x-kword',
		),
		'kwt' => array(
			'application/vnd.kde.kword',
			'application/x-kword',
		),
		'la' => array(
			'application/x-shared-library-la',
			'text/plain',
		),
		'lasjson' => array(
			'application/vnd.las.las+json',
		),
		'lasxml' => array(
			'application/vnd.las.las+xml',
		),
		'latex' => array(
			'application/x-latex',
			'application/x-tex',
			'text/plain',
			'text/x-tex',
		),
		'lbd' => array(
			'application/vnd.llamagraphics.life-balance.desktop',
		),
		'lbe' => array(
			'application/vnd.llamagraphics.life-balance.exchange+xml',
		),
		'lbm' => array(
			'application/x-iff',
			'image/x-iff',
			'image/x-ilbm',
		),
		'ldif' => array(
			'text/plain',
			'text/x-ldif',
		),
		'les' => array(
			'application/vnd.hhe.lesson-player',
		),
		'less' => array(
			'text/plain',
			'text/x-less',
		),
		'lgr' => array(
			'application/lgr+xml',
		),
		'lha' => array(
			'application/octet-stream',
			'application/x-lha',
			'application/x-lzh-compressed',
		),
		'lhs' => array(
			'text/plain',
			'text/x-haskell',
			'text/x-literate-haskell',
		),
		'lhz' => array(
			'application/x-lhz',
		),
		'link66' => array(
			'application/vnd.route66.link66+xml',
		),
		'lisp' => array(
			'text/plain',
			'text/x-common-lisp',
		),
		'list' => array(
			'text/plain',
		),
		'list3820' => array(
			'application/vnd.ibm.modcap',
		),
		'listafp' => array(
			'application/vnd.ibm.modcap',
		),
		'lnk' => array(
			'application/x-ms-shortcut',
		),
		'log' => array(
			'text/plain',
			'text/x-log',
		),
		'lostsyncxml' => array(
			'application/lostsync+xml',
		),
		'lostxml' => array(
			'application/lost+xml',
		),
		'lrf' => array(
			'application/octet-stream',
		),
		'lrm' => array(
			'application/vnd.ms-lrm',
		),
		'lrv' => array(
			'video/mp4',
			'video/mp4v-es',
			'video/x-m4v',
		),
		'lrz' => array(
			'application/x-lrzip',
		),
		'lsp' => array(
			'text/plain',
			'text/x-common-lisp',
		),
		'ltf' => array(
			'application/vnd.frogans.ltf',
		),
		'ltx' => array(
			'application/x-tex',
			'text/plain',
			'text/x-tex',
		),
		'lua' => array(
			'application/x-executable',
			'text/plain',
			'text/x-lua',
		),
		'lvp' => array(
			'audio/vnd.lucent.voice',
		),
		'lwo' => array(
			'image/x-lwo',
		),
		'lwob' => array(
			'image/x-lwo',
		),
		'lwp' => array(
			'application/vnd.lotus-wordpro',
		),
		'lws' => array(
			'image/x-lws',
		),
		'ly' => array(
			'text/plain',
			'text/x-lilypond',
		),
		'lyx' => array(
			'application/x-lyx',
			'text/plain',
			'text/x-lyx',
		),
		'lz' => array(
			'application/x-lzip',
		),
		'lz4' => array(
			'application/x-lz4',
		),
		'lzh' => array(
			'application/octet-stream',
			'application/x-lha',
			'application/x-lzh-compressed',
		),
		'lzma' => array(
			'application/x-lzma',
		),
		'lzo' => array(
			'application/x-lzop',
		),
		'm13' => array(
			'application/x-msmediaview',
		),
		'm14' => array(
			'application/x-msmediaview',
		),
		'm15' => array(
			'audio/x-mod',
		),
		'm1u' => array(
			'text/plain',
			'video/vnd.mpegurl',
			'video/x-mpegurl',
		),
		'm1v' => array(
			'video/mpeg',
		),
		'm21' => array(
			'application/mp21',
		),
		'm2a' => array(
			'audio/mpeg',
			'audio/x-mpeg',
		),
		'm2t' => array(
			'video/mp2t',
		),
		'm2ts' => array(
			'video/mp2t',
		),
		'm2v' => array(
			'video/mpeg',
		),
		'm3' => array(
			'text/plain',
			'text/x-modula',
		),
		'm3a' => array(
			'audio/mpeg',
			'audio/x-mpeg',
		),
		'm3u' => array(
			'application/m3u',
			'application/vnd.apple.mpegurl',
			'audio/m3u',
			'audio/mpegurl',
			'audio/x-m3u',
			'audio/x-mp3-playlist',
			'audio/x-mpegurl',
			'text/plain',
		),
		'm3u8' => array(
			'application/m3u',
			'application/vnd.apple.mpegurl',
			'audio/m3u',
			'audio/mpegurl',
			'audio/x-m3u',
			'audio/x-mp3-playlist',
			'audio/x-mpegurl',
			'text/plain',
		),
		'm4' => array(
			'application/x-m4',
			'text/plain',
		),
		'm4a' => array(
			'application/quicktime',
			'audio/m4a',
			'audio/mp4',
			'audio/x-m4a',
			'audio/x-mp4a',
		),
		'm4b' => array(
			'application/quicktime',
			'audio/mp4',
			'audio/x-m4a',
			'audio/x-m4b',
			'audio/x-mp4a',
		),
		'm4s' => array(
			'video/iso.segment',
		),
		'm4u' => array(
			'text/plain',
			'video/vnd.mpegurl',
			'video/x-mpegurl',
		),
		'm4v' => array(
			'video/mp4',
			'video/mp4v-es',
			'video/x-m4v',
		),
		'm7' => array(
			'application/x-thomson-cartridge-memo7',
		),
		'ma' => array(
			'application/mathematica',
		),
		'mab' => array(
			'application/x-markaby',
			'application/x-ruby',
		),
		'mads' => array(
			'application/mads+xml',
		),
		'mag' => array(
			'application/vnd.ecowin.chart',
		),
		'mak' => array(
			'text/plain',
			'text/x-makefile',
		),
		'makefile' => array(
			'text/plain',
			'text/x-makefile',
		),
		'maker' => array(
			'application/vnd.framemaker',
		),
		'man' => array(
			'application/x-troff',
			'application/x-troff-man',
			'application/x-troff-me',
			'application/x-troff-ms',
			'text/plain',
			'text/troff',
		),
		'manifest' => array(
			'text/cache-manifest',
			'text/plain',
		),
		'mar' => array(
			'application/octet-stream',
		),
		'markdown' => array(
			'text/markdown',
			'text/plain',
			'text/x-markdown',
			'text/x-web-markdown',
		),
		'mat' => array(
			'application/matlab-mat',
			'application/x-matlab-data',
		),
		'mathml' => array(
			'application/mathml+xml',
		),
		'mb' => array(
			'application/mathematica',
		),
		'mbk' => array(
			'application/vnd.mobius.mbk',
		),
		'mbox' => array(
			'application/mbox',
			'text/plain',
		),
		'mc1' => array(
			'application/vnd.medcalcdata',
		),
		'mcd' => array(
			'application/vnd.mcd',
			'application/vnd.vectorworks',
		),
		'mcurl' => array(
			'text/vnd.curl.mcurl',
		),
		'md' => array(
			'text/markdown',
			'text/plain',
			'text/x-markdown',
			'text/x-web-markdown',
		),
		'mdb' => array(
			'application/mdb',
			'application/msaccess',
			'application/vnd.ms-access',
			'application/vnd.msaccess',
			'application/x-mdb',
			'application/x-msaccess',
			'zz-application/zz-winassoc-mdb',
		),
		'mdi' => array(
			'image/vnd.ms-modi',
		),
		'mdtext' => array(
			'text/plain',
			'text/x-web-markdown',
		),
		'mdx' => array(
			'application/x-genesis-32x-rom',
		),
		'me' => array(
			'application/x-troff',
			'application/x-troff-man',
			'application/x-troff-me',
			'application/x-troff-ms',
			'text/plain',
			'text/troff',
			'text/x-troff-me',
		),
		'med' => array(
			'audio/x-mod',
		),
		'mef' => array(
			'image/x-raw-mamiya',
		),
		'mesh' => array(
			'model/mesh',
		),
		'meta' => array(
			'text/plain',
		),
		'meta4' => array(
			'application/metalink4+xml',
			'application/xml',
		),
		'metalink' => array(
			'application/metalink+xml',
			'application/xml',
		),
		'mets' => array(
			'application/mets+xml',
		),
		'mf' => array(
			'text/plain',
		),
		'mfm' => array(
			'application/vnd.mfmp',
		),
		'mft' => array(
			'application/rpki-manifest',
		),
		'mg' => array(
			'text/plain',
			'text/x-modula',
		),
		'mgp' => array(
			'application/vnd.osgeo.mapguide.package',
			'application/x-magicpoint',
			'text/plain',
		),
		'mgz' => array(
			'application/vnd.proteus.magazine',
		),
		'mht' => array(
			'application/x-mimearchive',
			'message/rfc822',
			'multipart/related',
		),
		'mhtml' => array(
			'application/x-mimearchive',
			'message/rfc822',
			'multipart/related',
		),
		'mid' => array(
			'audio/midi',
			'audio/sp-midi',
			'audio/x-midi',
		),
		'midi' => array(
			'audio/midi',
			'audio/x-midi',
		),
		'mie' => array(
			'application/x-mie',
		),
		'mif' => array(
			'application/vnd.mif',
			'application/x-frame',
			'application/x-mif',
		),
		'mime' => array(
			'message/rfc822',
		),
		'minipsf' => array(
			'audio/x-minipsf',
			'audio/x-psf',
		),
		'mj2' => array(
			'image/x-jp2-container',
			'video/mj2',
		),
		'mjp2' => array(
			'image/x-jp2-container',
			'video/mj2',
		),
		'mk' => array(
			'text/plain',
			'text/x-makefile',
		),
		'mk3d' => array(
			'application/x-matroska',
			'video/x-matroska',
			'video/x-matroska-3d',
		),
		'mka' => array(
			'application/x-matroska',
			'audio/x-matroska',
		),
		'mkd' => array(
			'text/markdown',
			'text/plain',
			'text/x-markdown',
			'text/x-web-markdown',
		),
		'mks' => array(
			'video/x-matroska',
		),
		'mkv' => array(
			'application/x-matroska',
			'video/x-matroska',
		),
		'ml' => array(
			'text/plain',
			'text/x-ml',
			'text/x-ocaml',
		),
		'mli' => array(
			'text/plain',
			'text/x-ocaml',
		),
		'mlp' => array(
			'application/vnd.dolby.mlp',
		),
		'mm' => array(
			'text/plain',
			'text/x-troff-mm',
		),
		'mmap' => array(
			'application/vnd.mindjet.mindmanager',
			'application/zip',
		),
		'mmas' => array(
			'application/vnd.mindjet.mindmanager',
			'application/zip',
		),
		'mmat' => array(
			'application/vnd.mindjet.mindmanager',
			'application/zip',
		),
		'mmd' => array(
			'application/vnd.chipnuts.karaoke-mmd',
		),
		'mmf' => array(
			'application/vnd.smaf',
			'application/x-smaf',
		),
		'mml' => array(
			'application/mathml+xml',
			'application/xml',
			'text/mathml',
		),
		'mmmp' => array(
			'application/vnd.mindjet.mindmanager',
			'application/zip',
		),
		'mmp' => array(
			'application/vnd.mindjet.mindmanager',
			'application/zip',
		),
		'mmpt' => array(
			'application/vnd.mindjet.mindmanager',
			'application/zip',
		),
		'mmr' => array(
			'image/vnd.fujixerox.edmics-mmr',
		),
		'mng' => array(
			'video/x-mng',
		),
		'mny' => array(
			'application/x-msmoney',
		),
		'mo' => array(
			'application/x-gettext-translation',
			'text/plain',
			'text/x-modelica',
		),
		'mo3' => array(
			'audio/x-mo3',
		),
		'mobi' => array(
			'application/vnd.palm',
			'application/x-mobipocket-ebook',
		),
		'moc' => array(
			'text/plain',
			'text/x-moc',
		),
		'mod' => array(
			'application/xml-dtd',
			'audio/x-mod',
		),
		'mods' => array(
			'application/mods+xml',
		),
		'mof' => array(
			'text/x-csrc',
			'text/x-mof',
		),
		'moov' => array(
			'video/quicktime',
		),
		'mos' => array(
			'image/x-raw-leaf',
		),
		'mount' => array(
			'text/plain',
			'text/x-systemd-unit',
		),
		'mov' => array(
			'application/quicktime',
			'video/quicktime',
		),
		'movie' => array(
			'video/x-sgi-movie',
		),
		'mp1' => array(
			'audio/mpeg',
		),
		'mp2' => array(
			'audio/mp2',
			'audio/mpeg',
			'audio/x-mp2',
			'audio/x-mpeg',
			'video/mpeg',
			'video/mpeg-system',
			'video/x-mpeg',
			'video/x-mpeg-system',
			'video/x-mpeg2',
		),
		'mp21' => array(
			'application/mp21',
		),
		'mp2a' => array(
			'audio/mpeg',
			'audio/x-mpeg',
		),
		'mp3' => array(
			'audio/mp3',
			'audio/mpeg',
			'audio/x-mp3',
			'audio/x-mpeg',
			'audio/x-mpg',
		),
		'mp4' => array(
			'video/mp4',
			'video/mp4v-es',
			'video/quicktime',
			'video/x-m4v',
		),
		'mp4a' => array(
			'application/quicktime',
			'audio/mp4',
			'audio/x-m4a',
			'audio/x-mp4a',
		),
		'mp4s' => array(
			'application/mp4',
			'application/quicktime',
		),
		'mp4v' => array(
			'video/mp4',
			'video/quicktime',
		),
		'mpc' => array(
			'application/vnd.mophun.certificate',
			'audio/x-musepack',
		),
		'mpe' => array(
			'video/mpeg',
			'video/mpeg-system',
			'video/x-mpeg',
			'video/x-mpeg-system',
			'video/x-mpeg2',
		),
		'mpeg' => array(
			'video/mpeg',
			'video/mpeg-system',
			'video/x-mpeg',
			'video/x-mpeg-system',
			'video/x-mpeg2',
		),
		'mpf' => array(
			'application/media-policy-dataset+xml',
			'text/vnd.ms-mediapackage',
		),
		'mpg' => array(
			'video/mpeg',
			'video/mpeg-system',
			'video/x-mpeg',
			'video/x-mpeg-system',
			'video/x-mpeg2',
		),
		'mpg4' => array(
			'video/mp4',
			'video/quicktime',
		),
		'mpga' => array(
			'audio/mp3',
			'audio/mpeg',
			'audio/x-mp3',
			'audio/x-mpeg',
			'audio/x-mpg',
		),
		'mpkg' => array(
			'application/vnd.apple.installer+xml',
		),
		'mpl' => array(
			'video/mp2t',
		),
		'mpls' => array(
			'video/mp2t',
		),
		'mpm' => array(
			'application/vnd.blueice.multipass',
		),
		'mpn' => array(
			'application/vnd.mophun.application',
		),
		'mpp' => array(
			'application/vnd.ms-project',
			'audio/x-musepack',
		),
		'mpt' => array(
			'application/vnd.ms-project',
		),
		'mpx' => array(
			'application/x-project',
			'text/plain',
		),
		'mpy' => array(
			'application/vnd.ibm.minipay',
		),
		'mqy' => array(
			'application/vnd.mobius.mqy',
		),
		'mrc' => array(
			'application/marc',
		),
		'mrcx' => array(
			'application/marcxml+xml',
		),
		'mrl' => array(
			'text/x-mrml',
		),
		'mrml' => array(
			'text/x-mrml',
		),
		'mrw' => array(
			'image/x-dcraw',
			'image/x-minolta-mrw',
			'image/x-raw-minolta',
		),
		'ms' => array(
			'application/x-troff',
			'application/x-troff-man',
			'application/x-troff-me',
			'application/x-troff-ms',
			'text/plain',
			'text/troff',
			'text/x-troff-ms',
		),
		'mscml' => array(
			'application/mediaservercontrol+xml',
		),
		'mseed' => array(
			'application/vnd.fdsn.mseed',
		),
		'mseq' => array(
			'application/vnd.mseq',
		),
		'msf' => array(
			'application/vnd.epson.msf',
		),
		'msg' => array(
			'application/vnd.ms-outlook',
		),
		'msh' => array(
			'model/mesh',
		),
		'msi' => array(
			'application/octet-stream',
			'application/x-ms-installer',
			'application/x-msdownload',
			'application/x-msi',
			'application/x-ole-storage',
			'application/x-windows-installer',
		),
		'msl' => array(
			'application/vnd.mobius.msl',
		),
		'msm' => array(
			'application/octet-stream',
		),
		'msod' => array(
			'image/x-msod',
		),
		'msp' => array(
			'application/octet-stream',
			'application/x-ms-installer',
			'application/x-msi',
			'application/x-windows-installer',
		),
		'mst' => array(
			'application/x-ms-installer',
			'application/x-msi',
			'application/x-windows-installer',
		),
		'msty' => array(
			'application/vnd.muvee.style',
		),
		'msx' => array(
			'application/x-msx-rom',
		),
		'mtm' => array(
			'audio/x-mod',
		),
		'mts' => array(
			'model/vnd.mts',
			'video/mp2t',
		),
		'mup' => array(
			'text/plain',
			'text/x-mup',
		),
		'mus' => array(
			'application/vnd.musician',
		),
		'musicxml' => array(
			'application/vnd.recordare.musicxml+xml',
		),
		'mvb' => array(
			'application/x-msmediaview',
		),
		'mvt' => array(
			'application/vnd.mapbox-vector-tile',
		),
		'mwf' => array(
			'application/vnd.mfer',
		),
		'mxf' => array(
			'application/mxf',
		),
		'mxl' => array(
			'application/vnd.recordare.musicxml',
		),
		'mxmf' => array(
			'audio/mobile-xmf',
			'audio/vnd.nokia.mobile-xmf',
		),
		'mxml' => array(
			'application/xv+xml',
		),
		'mxs' => array(
			'application/vnd.triscape.mxs',
		),
		'mxu' => array(
			'text/plain',
			'video/vnd.mpegurl',
			'video/x-mpegurl',
		),
		'n-gage' => array(
			'application/vnd.nokia.n-gage.symbian.install',
		),
		'n3' => array(
			'text/n3',
			'text/plain',
		),
		'n64' => array(
			'application/x-n64-rom',
		),
		'nb' => array(
			'application/mathematica',
			'application/x-mathematica',
			'text/plain',
		),
		'nbp' => array(
			'application/vnd.wolfram.player',
		),
		'nc' => array(
			'application/x-netcdf',
		),
		'ncx' => array(
			'application/x-dtbncx+xml',
		),
		'nds' => array(
			'application/x-nintendo-ds-rom',
		),
		'nef' => array(
			'image/x-dcraw',
			'image/x-nikon-nef',
			'image/x-raw-nikon',
		),
		'nes' => array(
			'application/x-nes-rom',
		),
		'nez' => array(
			'application/x-nes-rom',
		),
		'nfo' => array(
			'text/x-nfo',
			'text/x-readme',
		),
		'ngdat' => array(
			'application/vnd.nokia.n-gage.data',
		),
		'ngp' => array(
			'application/x-neo-geo-pocket-rom',
		),
		'nitf' => array(
			'application/vnd.nitf',
			'image/nitf',
			'image/ntf',
		),
		'nlu' => array(
			'application/vnd.neurolanguage.nlu',
		),
		'nml' => array(
			'application/vnd.enliven',
		),
		'nnd' => array(
			'application/vnd.noblenet-directory',
		),
		'nns' => array(
			'application/vnd.noblenet-sealer',
		),
		'nnw' => array(
			'application/vnd.noblenet-web',
		),
		'not' => array(
			'text/plain',
			'text/x-mup',
		),
		'npx' => array(
			'image/vnd.net-fpx',
		),
		'nrw' => array(
			'image/x-raw-nikon',
		),
		'nsc' => array(
			'application/vnd.ms-asf',
			'application/x-conference',
			'application/x-netshow-channel',
		),
		'nsf' => array(
			'application/vnd.lotus-notes',
		),
		'nsv' => array(
			'video/x-nsv',
		),
		'ntf' => array(
			'application/vnd.nitf',
			'image/nitf',
			'image/ntf',
		),
		'numbers' => array(
			'application/vnd.apple.iwork',
			'application/vnd.apple.numbers',
		),
		'nzb' => array(
			'application/x-nzb',
			'application/xml',
		),
		'oa2' => array(
			'application/vnd.fujitsu.oasys2',
		),
		'oa3' => array(
			'application/vnd.fujitsu.oasys3',
		),
		'oas' => array(
			'application/vnd.fujitsu.oasys',
		),
		'obd' => array(
			'application/x-msbinder',
		),
		'obj' => array(
			'application/x-tgif',
		),
		'ocaml' => array(
			'text/plain',
			'text/x-ocaml',
		),
		'ocl' => array(
			'text/plain',
			'text/x-ocl',
		),
		'oda' => array(
			'application/oda',
		),
		'odb' => array(
			'application/vnd.oasis.opendocument.database',
			'application/vnd.sun.xml.base',
			'application/zip',
		),
		'odc' => array(
			'application/vnd.oasis.opendocument.chart',
			'application/x-vnd.oasis.opendocument.chart',
			'application/zip',
		),
		'odf' => array(
			'application/vnd.oasis.opendocument.formula',
			'application/x-vnd.oasis.opendocument.formula',
			'application/zip',
		),
		'odft' => array(
			'application/vnd.oasis.opendocument.formula-template',
			'application/x-vnd.oasis.opendocument.formula-template',
		),
		'odg' => array(
			'application/vnd.oasis.opendocument.graphics',
			'application/x-vnd.oasis.opendocument.graphics',
			'application/zip',
		),
		'odi' => array(
			'application/vnd.oasis.opendocument.image',
			'application/x-vnd.oasis.opendocument.image',
			'application/zip',
		),
		'odm' => array(
			'application/vnd.oasis.opendocument.text-master',
			'application/zip',
		),
		'odp' => array(
			'application/vnd.oasis.opendocument.presentation',
			'application/x-vnd.oasis.opendocument.presentation',
			'application/zip',
		),
		'ods' => array(
			'application/vnd.oasis.opendocument.spreadsheet',
			'application/x-vnd.oasis.opendocument.spreadsheet',
			'application/zip',
		),
		'odt' => array(
			'application/vnd.oasis.opendocument.text',
			'application/x-vnd.oasis.opendocument.text',
			'application/zip',
		),
		'oga' => array(
			'application/ogg',
			'audio/ogg',
			'audio/vorbis',
			'audio/x-flac+ogg',
			'audio/x-ogg',
			'audio/x-oggflac',
			'audio/x-speex+ogg',
			'audio/x-vorbis',
			'audio/x-vorbis+ogg',
		),
		'ogg' => array(
			'application/ogg',
			'application/x-ogg',
			'audio/ogg',
			'audio/vorbis',
			'audio/x-flac+ogg',
			'audio/x-ogg',
			'audio/x-oggflac',
			'audio/x-speex+ogg',
			'audio/x-vorbis',
			'audio/x-vorbis+ogg',
			'video/ogg',
			'video/x-ogg',
			'video/x-theora',
			'video/x-theora+ogg',
		),
		'ogm' => array(
			'video/ogg',
			'video/x-ogm',
			'video/x-ogm+ogg',
		),
		'ogv' => array(
			'application/ogg',
			'video/ogg',
			'video/x-ogg',
		),
		'ogx' => array(
			'application/ogg',
			'application/x-ogg',
		),
		'old' => array(
			'application/x-trash',
		),
		'oleo' => array(
			'application/x-oleo',
		),
		'omdoc' => array(
			'application/omdoc+xml',
		),
		'one' => array(
			'application/onenote',
			'application/onenoteformatone',
		),
		'onepkg' => array(
			'application/onenote',
			'application/onenoteformatpackage',
			'application/vnd.ms-cab-compressed',
		),
		'onetmp' => array(
			'application/msonenote',
			'application/onenote',
		),
		'onetoc' => array(
			'application/onenote',
			'application/onenoteformatonetoc2',
		),
		'onetoc2' => array(
			'application/onenote',
			'application/onenoteformatonetoc2',
		),
		'ooc' => array(
			'text/x-csrc',
			'text/x-ooc',
		),
		'opf' => array(
			'application/oebps-package+xml',
		),
		'opml' => array(
			'application/xml',
			'text/x-opml',
			'text/x-opml+xml',
		),
		'oprc' => array(
			'application/vnd.palm',
			'application/x-palm-database',
		),
		'opus' => array(
			'application/ogg',
			'audio/ogg',
			'audio/opus',
			'audio/x-ogg',
			'audio/x-opus+ogg',
		),
		'or3' => array(
			'application/vnd.lotus-organizer',
		),
		'ora' => array(
			'application/zip',
			'image/openraster',
		),
		'orf' => array(
			'image/x-dcraw',
			'image/x-olympus-orf',
			'image/x-raw-olympus',
		),
		'org' => array(
			'application/vnd.lotus-organizer',
		),
		'orq' => array(
			'application/ocsp-request',
		),
		'ors' => array(
			'application/ocsp-response',
		),
		'osf' => array(
			'application/vnd.yamaha.openscoreformat',
		),
		'osfpvg' => array(
			'application/vnd.yamaha.openscoreformat.osfpvg+xml',
		),
		'osm' => array(
			'application/vnd.openstreetmap.data+xml',
		),
		'ost' => array(
			'application/vnd.ms-outlook-pst',
		),
		'otc' => array(
			'application/vnd.oasis.opendocument.chart-template',
			'application/x-vnd.oasis.opendocument.chart-template',
			'application/zip',
		),
		'otf' => array(
			'application/vnd.oasis.opendocument.formula-template',
			'application/x-font-otf',
			'application/zip',
			'font/otf',
			'font/ttf',
		),
		'otg' => array(
			'application/vnd.oasis.opendocument.graphics-template',
			'application/x-vnd.oasis.opendocument.graphics-template',
			'application/zip',
		),
		'oth' => array(
			'application/vnd.oasis.opendocument.text-web',
			'application/x-vnd.oasis.opendocument.text-web',
			'application/zip',
		),
		'oti' => array(
			'application/vnd.oasis.opendocument.image-template',
			'application/x-vnd.oasis.opendocument.image-template',
		),
		'otm' => array(
			'application/vnd.oasis.opendocument.text-master',
			'application/x-vnd.oasis.opendocument.text-master',
		),
		'otp' => array(
			'application/vnd.oasis.opendocument.presentation-template',
			'application/x-vnd.oasis.opendocument.presentation-template',
			'application/zip',
		),
		'ots' => array(
			'application/vnd.oasis.opendocument.spreadsheet-template',
			'application/x-vnd.oasis.opendocument.spreadsheet-template',
			'application/zip',
		),
		'ott' => array(
			'application/vnd.oasis.opendocument.text-template',
			'application/x-vnd.oasis.opendocument.text-template',
			'application/zip',
		),
		'owl' => array(
			'application/rdf+xml',
			'application/xml',
			'text/rdf',
		),
		'owx' => array(
			'application/owl+xml',
			'application/xml',
		),
		'oxps' => array(
			'application/oxps',
			'application/vnd.ms-xpsdocument',
			'application/zip',
		),
		'oxt' => array(
			'application/vnd.openofficeorg.extension',
			'application/zip',
		),
		'p' => array(
			'text/x-pascal',
		),
		'p10' => array(
			'application/pkcs10',
		),
		'p12' => array(
			'application/pkcs12',
			'application/x-pkcs12',
		),
		'p65' => array(
			'application/x-ole-storage',
			'application/x-pagemaker',
		),
		'p7b' => array(
			'application/x-pkcs7-certificates',
		),
		'p7c' => array(
			'application/pkcs7-mime',
		),
		'p7m' => array(
			'application/pkcs7-mime',
		),
		'p7r' => array(
			'application/x-pkcs7-certreqresp',
		),
		'p7s' => array(
			'application/pkcs7-signature',
			'text/plain',
		),
		'p8' => array(
			'application/pkcs8',
		),
		'pack' => array(
			'application/x-java-pack200',
		),
		'pages' => array(
			'application/vnd.apple.iwork',
			'application/vnd.apple.pages',
		),
		'pak' => array(
			'application/x-pak',
		),
		'par2' => array(
			'application/x-par2',
		),
		'part' => array(
			'application/x-partial-download',
		),
		'pas' => array(
			'text/plain',
			'text/x-pascal',
		),
		'patch' => array(
			'text/plain',
			'text/x-diff',
			'text/x-patch',
		),
		'path' => array(
			'text/plain',
			'text/x-systemd-unit',
		),
		'paw' => array(
			'application/vnd.pawaafile',
		),
		'pbd' => array(
			'application/vnd.powerbuilder6',
		),
		'pbm' => array(
			'image/x-portable-anymap',
			'image/x-portable-bitmap',
		),
		'pcap' => array(
			'application/pcap',
			'application/vnd.tcpdump.pcap',
			'application/x-pcap',
		),
		'pcd' => array(
			'image/x-photo-cd',
		),
		'pce' => array(
			'application/x-pc-engine-rom',
		),
		'pcf' => array(
			'application/x-cisco-vpn-settings',
			'application/x-font-pcf',
		),
		'pcl' => array(
			'application/vnd.hp-pcl',
		),
		'pclxl' => array(
			'application/vnd.hp-pclxl',
		),
		'pct' => array(
			'image/x-pict',
		),
		'pcurl' => array(
			'application/vnd.curl.pcurl',
		),
		'pcx' => array(
			'image/vnd.zbrush.pcx',
			'image/x-pcx',
		),
		'pdb' => array(
			'application/vnd.palm',
			'application/x-aportisdoc',
			'application/x-palm-database',
			'application/x-pilot',
			'chemical/x-pdb',
		),
		'pdc' => array(
			'application/vnd.palm',
			'application/x-aportisdoc',
		),
		'pdf' => array(
			'application/acrobat',
			'application/nappdf',
			'application/pdf',
			'application/x-pdf',
			'image/pdf',
		),
		'pef' => array(
			'image/x-dcraw',
			'image/x-pentax-pef',
			'image/x-raw-pentax',
		),
		'pem' => array(
			'application/x-x509-ca-cert',
		),
		'pen' => array(
			'text/plain',
		),
		'perl' => array(
			'application/x-executable',
			'application/x-perl',
			'text/plain',
			'text/x-perl',
		),
		'pfa' => array(
			'application/postscript',
			'application/x-font-type1',
		),
		'pfb' => array(
			'application/postscript',
			'application/x-font-type1',
		),
		'pfm' => array(
			'application/x-font-printer-metric',
			'application/x-font-type1',
		),
		'pfr' => array(
			'application/font-tdpfr',
		),
		'pfx' => array(
			'application/pkcs12',
			'application/x-pkcs12',
		),
		'pgm' => array(
			'image/x-portable-anymap',
			'image/x-portable-graymap',
		),
		'pgn' => array(
			'application/vnd.chess-pgn',
			'application/x-chess-pgn',
			'text/plain',
		),
		'pgp' => array(
			'application/pgp',
			'application/pgp-encrypted',
			'application/pgp-keys',
			'application/pgp-signature',
			'text/plain',
		),
		'php' => array(
			'application/x-php',
			'text/plain',
			'text/x-php',
		),
		'php3' => array(
			'application/x-php',
			'text/plain',
			'text/x-php',
		),
		'php4' => array(
			'application/x-php',
			'text/plain',
			'text/x-php',
		),
		'php5' => array(
			'application/x-php',
			'text/plain',
		),
		'phps' => array(
			'application/x-php',
			'text/plain',
		),
		'pic' => array(
			'image/vnd.radiance',
			'image/x-pict',
		),
		'pict' => array(
			'image/x-pict',
		),
		'pict1' => array(
			'image/x-pict',
		),
		'pict2' => array(
			'image/x-pict',
		),
		'pk' => array(
			'application/x-tex-pk',
		),
		'pkg' => array(
			'application/octet-stream',
			'application/x-xar',
		),
		'pki' => array(
			'application/pkixcmp',
		),
		'pkipath' => array(
			'application/pkix-pkipath',
		),
		'pkr' => array(
			'application/pgp-keys',
			'text/plain',
		),
		'pl' => array(
			'application/x-executable',
			'application/x-perl',
			'text/plain',
			'text/x-perl',
		),
		'pla' => array(
			'audio/x-iriver-pla',
		),
		'plb' => array(
			'application/vnd.3gpp.pic-bw-large',
		),
		'plc' => array(
			'application/vnd.mobius.plc',
		),
		'plf' => array(
			'application/vnd.pocketlearn',
		),
		'pln' => array(
			'application/x-planperfect',
		),
		'pls' => array(
			'application/pls',
			'application/pls+xml',
			'audio/scpls',
			'audio/x-scpls',
		),
		'pm' => array(
			'application/x-executable',
			'application/x-ole-storage',
			'application/x-pagemaker',
			'application/x-perl',
			'text/plain',
			'text/x-perl',
		),
		'pm6' => array(
			'application/x-ole-storage',
			'application/x-pagemaker',
		),
		'pmd' => array(
			'application/x-ole-storage',
			'application/x-pagemaker',
		),
		'pml' => array(
			'application/vnd.ctc-posml',
		),
		'png' => array(
			'image/png',
		),
		'pnm' => array(
			'image/x-portable-anymap',
		),
		'pntg' => array(
			'image/x-macpaint',
		),
		'po' => array(
			'application/x-gettext',
			'text/plain',
			'text/x-gettext-translation',
			'text/x-po',
		),
		'pod' => array(
			'application/x-executable',
			'application/x-perl',
			'text/plain',
		),
		'pom' => array(
			'text/plain',
		),
		'por' => array(
			'application/x-spss-por',
		),
		'portpkg' => array(
			'application/vnd.macports.portpkg',
		),
		'pot' => array(
			'application/mspowerpoint',
			'application/powerpoint',
			'application/vnd.ms-office',
			'application/vnd.ms-powerpoint',
			'application/x-mspowerpoint',
			'text/plain',
			'text/x-gettext-translation-template',
			'text/x-pot',
		),
		'potm' => array(
			'application/vnd.ms-powerpoint.template.macroenabled.12',
			'application/vnd.openxmlformats-officedocument.presentationml.template',
		),
		'potx' => array(
			'application/vnd.openxmlformats-officedocument.presentationml.template',
			'application/zip',
		),
		'pp' => array(
			'text/plain',
			'text/x-pascal',
		),
		'ppa' => array(
			'application/mspowerpoint',
			'application/vnd.ms-office',
			'application/vnd.ms-powerpoint',
		),
		'ppam' => array(
			'application/vnd.ms-powerpoint.addin.macroenabled.12',
		),
		'ppd' => array(
			'application/vnd.cups-ppd',
		),
		'ppj' => array(
			'application/xml',
			'image/vnd.adobe.premiere',
		),
		'ppm' => array(
			'image/x-portable-anymap',
			'image/x-portable-pixmap',
		),
		'pps' => array(
			'application/mspowerpoint',
			'application/powerpoint',
			'application/vnd.ms-office',
			'application/vnd.ms-powerpoint',
			'application/x-mspowerpoint',
		),
		'ppsm' => array(
			'application/vnd.ms-powerpoint.slideshow.macroenabled.12',
			'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
		),
		'ppsx' => array(
			'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
			'application/zip',
		),
		'ppt' => array(
			'application/mspowerpoint',
			'application/powerpoint',
			'application/vnd.ms-office',
			'application/vnd.ms-powerpoint',
			'application/x-mspowerpoint',
		),
		'pptm' => array(
			'application/vnd.ms-powerpoint.presentation.macroenabled.12',
			'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		),
		'pptx' => array(
			'application/vnd.openxmlformats-officedocument.presentationml.presentation',
			'application/zip',
		),
		'ppz' => array(
			'application/mspowerpoint',
			'application/powerpoint',
			'application/vnd.ms-office',
			'application/vnd.ms-powerpoint',
			'application/x-mspowerpoint',
		),
		'pqa' => array(
			'application/vnd.palm',
			'application/x-palm-database',
		),
		'prc' => array(
			'application/vnd.palm',
			'application/x-mobipocket-ebook',
			'application/x-palm-database',
			'application/x-pilot',
		),
		'pre' => array(
			'application/vnd.lotus-freelance',
		),
		'prf' => array(
			'application/pics-rules',
		),
		'pro' => array(
			'text/plain',
			'text/x-prolog',
		),
		'project' => array(
			'text/plain',
		),
		'properties' => array(
			'text/plain',
			'text/properties',
			'text/x-java-properties',
			'text/x-properties',
		),
		'provx' => array(
			'application/provenance+xml',
		),
		'prt' => array(
			'application/x-prt',
		),
		'prz' => array(
			'application/vnd.lotus-freelance',
		),
		'ps' => array(
			'application/postscript',
			'text/plain',
		),
		'psb' => array(
			'application/vnd.3gpp.pic-bw-small',
		),
		'psd' => array(
			'application/photoshop',
			'application/x-photoshop',
			'image/photoshop',
			'image/psd',
			'image/vnd.adobe.photoshop',
			'image/x-photoshop',
			'image/x-psd',
		),
		'pseg3820' => array(
			'application/vnd.ibm.modcap',
		),
		'psf' => array(
			'application/x-font-linux-psf',
			'audio/x-psf',
		),
		'psflib' => array(
			'audio/x-psf',
			'audio/x-psflib',
		),
		'psid' => array(
			'audio/prs.sid',
		),
		'pskcxml' => array(
			'application/pskc+xml',
		),
		'pst' => array(
			'application/vnd.ms-outlook-pst',
		),
		'psw' => array(
			'application/x-pocket-word',
		),
		'ptid' => array(
			'application/vnd.pvi.ptid1',
		),
		'ptx' => array(
			'image/x-raw-pentax',
		),
		'pub' => array(
			'application/vnd.ms-publisher',
			'application/x-mspublisher',
			'application/x-ole-storage',
		),
		'pvb' => array(
			'application/vnd.3gpp.pic-bw-var',
		),
		'pw' => array(
			'application/x-pw',
		),
		'pwn' => array(
			'application/vnd.3m.post-it-notes',
		),
		'pxn' => array(
			'image/x-raw-logitech',
		),
		'py' => array(
			'application/x-executable',
			'text/plain',
			'text/x-python',
			'text/x-python3',
		),
		'py3' => array(
			'text/x-python',
			'text/x-python3',
		),
		'py3x' => array(
			'text/x-python',
			'text/x-python3',
		),
		'pya' => array(
			'audio/vnd.ms-playready.media.pya',
		),
		'pyc' => array(
			'application/x-python-bytecode',
		),
		'pyo' => array(
			'application/x-python-bytecode',
		),
		'pyv' => array(
			'video/vnd.ms-playready.media.pyv',
		),
		'pyx' => array(
			'application/x-executable',
			'text/x-python',
		),
		'qam' => array(
			'application/vnd.epson.quickanime',
		),
		'qbo' => array(
			'application/vnd.intu.qbo',
		),
		'qca' => array(
			'application/vnd.ericsson.quickcall',
		),
		'qcall' => array(
			'application/vnd.ericsson.quickcall',
		),
		'qfx' => array(
			'application/vnd.intu.qfx',
		),
		'qif' => array(
			'application/x-qw',
			'image/x-quicktime',
		),
		'qml' => array(
			'text/x-qml',
		),
		'qmlproject' => array(
			'text/x-qml',
		),
		'qmltypes' => array(
			'text/x-qml',
		),
		'qp' => array(
			'application/x-qpress',
		),
		'qps' => array(
			'application/vnd.publishare-delta-tree',
		),
		'qpw' => array(
			'application/x-quattro-pro',
		),
		'qt' => array(
			'application/quicktime',
			'video/quicktime',
		),
		'qti' => array(
			'application/x-qtiplot',
			'text/plain',
		),
		'qtif' => array(
			'image/x-quicktime',
		),
		'qtl' => array(
			'application/x-quicktime-media-link',
			'application/x-quicktimeplayer',
			'video/quicktime',
		),
		'qtvr' => array(
			'video/quicktime',
		),
		'qwd' => array(
			'application/vnd.quark.quarkxpress',
		),
		'qwt' => array(
			'application/vnd.quark.quarkxpress',
		),
		'qxb' => array(
			'application/vnd.quark.quarkxpress',
		),
		'qxd' => array(
			'application/vnd.quark.quarkxpress',
		),
		'qxl' => array(
			'application/vnd.quark.quarkxpress',
		),
		'qxt' => array(
			'application/vnd.quark.quarkxpress',
		),
		'r3d' => array(
			'image/x-raw-red',
		),
		'ra' => array(
			'audio/vnd.m-realaudio',
			'audio/vnd.rn-realaudio',
			'audio/x-pn-realaudio',
			'audio/x-realaudio',
		),
		'raf' => array(
			'image/x-dcraw',
			'image/x-fuji-raf',
			'image/x-raw-fuji',
		),
		'ram' => array(
			'application/ram',
			'audio/x-pn-realaudio',
			'audio/x-realaudio',
		),
		'raml' => array(
			'application/raml+yaml',
			'application/x-yaml',
		),
		'rar' => array(
			'application/vnd.rar',
			'application/x-rar',
			'application/x-rar-compressed',
		),
		'ras' => array(
			'image/x-cmu-raster',
		),
		'raw' => array(
			'image/x-dcraw',
			'image/x-panasonic-raw',
			'image/x-panasonic-rw',
			'image/x-raw-panasonic',
		),
		'raw-disk-image' => array(
			'application/x-raw-disk-image',
		),
		'rax' => array(
			'audio/vnd.m-realaudio',
			'audio/vnd.rn-realaudio',
			'audio/x-pn-realaudio',
		),
		'rb' => array(
			'application/x-executable',
			'application/x-ruby',
			'text/plain',
			'text/x-ruby',
		),
		'rcprofile' => array(
			'application/vnd.ipunplugged.rcprofile',
		),
		'rdf' => array(
			'application/rdf+xml',
			'application/xml',
			'text/rdf',
		),
		'rdfs' => array(
			'application/rdf+xml',
			'application/xml',
			'text/rdf',
		),
		'rdz' => array(
			'application/vnd.data-vision.rdz',
		),
		'reg' => array(
			'text/plain',
			'text/x-ms-regedit',
		),
		'rej' => array(
			'application/x-reject',
			'text/plain',
			'text/x-reject',
		),
		'relo' => array(
			'application/p2p-overlay+xml',
		),
		'rep' => array(
			'application/vnd.businessobjects',
		),
		'res' => array(
			'application/x-dtbresource+xml',
		),
		'rest' => array(
			'text/plain',
			'text/x-rst',
		),
		'restx' => array(
			'text/plain',
			'text/x-rst',
		),
		'rexx' => array(
			'text/plain',
			'text/x-rexx',
		),
		'rgb' => array(
			'image/x-rgb',
		),
		'rgbe' => array(
			'image/vnd.radiance',
		),
		'rif' => array(
			'application/reginfo+xml',
		),
		'rip' => array(
			'audio/vnd.rip',
		),
		'ris' => array(
			'application/x-research-info-systems',
		),
		'rl' => array(
			'application/resource-lists+xml',
		),
		'rlc' => array(
			'image/vnd.fujixerox.edmics-rlc',
		),
		'rld' => array(
			'application/resource-lists-diff+xml',
		),
		'rle' => array(
			'image/rle',
		),
		'rm' => array(
			'application/vnd.rn-realmedia',
			'application/vnd.rn-realmedia-vbr',
		),
		'rmi' => array(
			'audio/midi',
		),
		'rmj' => array(
			'application/vnd.rn-realmedia',
			'application/vnd.rn-realmedia-vbr',
		),
		'rmm' => array(
			'application/vnd.rn-realmedia',
			'application/vnd.rn-realmedia-vbr',
		),
		'rmp' => array(
			'audio/x-pn-realaudio-plugin',
		),
		'rms' => array(
			'application/vnd.jcp.javame.midlet-rms',
			'application/vnd.rn-realmedia',
			'application/vnd.rn-realmedia-vbr',
		),
		'rmvb' => array(
			'application/vnd.rn-realmedia',
			'application/vnd.rn-realmedia-vbr',
		),
		'rmx' => array(
			'application/vnd.rn-realmedia',
			'application/vnd.rn-realmedia-vbr',
		),
		'rnc' => array(
			'application/relax-ng-compact-syntax',
			'application/x-rnc',
			'text/plain',
		),
		'rng' => array(
			'application/xml',
			'text/plain',
			'text/xml',
		),
		'rnx' => array(
			'text/plain',
		),
		'roa' => array(
			'application/rpki-roa',
		),
		'roff' => array(
			'application/x-troff',
			'application/x-troff-man',
			'application/x-troff-me',
			'application/x-troff-ms',
			'text/plain',
			'text/troff',
			'text/x-troff',
		),
		'roles' => array(
			'text/plain',
		),
		'rp' => array(
			'image/vnd.rn-realpix',
		),
		'rp9' => array(
			'application/vnd.cloanto.rp9',
		),
		'rpm' => array(
			'application/x-redhat-package-manager',
			'application/x-rpm',
		),
		'rpss' => array(
			'application/vnd.nokia.radio-presets',
		),
		'rpst' => array(
			'application/vnd.nokia.radio-preset',
		),
		'rq' => array(
			'application/sparql-query',
		),
		'rs' => array(
			'application/rls-services+xml',
			'text/plain',
			'text/rust',
		),
		'rsd' => array(
			'application/rsd+xml',
		),
		'rsheet' => array(
			'application/urc-ressheet+xml',
		),
		'rss' => array(
			'application/rss+xml',
			'application/xml',
			'text/rss',
		),
		'rst' => array(
			'text/plain',
			'text/x-rst',
		),
		'rt' => array(
			'text/vnd.rn-realtext',
		),
		'rtf' => array(
			'application/rtf',
			'text/plain',
			'text/rtf',
		),
		'rtx' => array(
			'text/plain',
			'text/richtext',
		),
		'run' => array(
			'application/x-makeself',
		),
		'rv' => array(
			'video/vnd.rn-realvideo',
			'video/x-real-video',
		),
		'rvx' => array(
			'video/vnd.rn-realvideo',
			'video/x-real-video',
		),
		'rw2' => array(
			'image/x-dcraw',
			'image/x-panasonic-raw2',
			'image/x-panasonic-rw2',
			'image/x-raw-panasonic',
		),
		'rwz' => array(
			'image/x-raw-rawzor',
		),
		's' => array(
			'text/x-asm',
		),
		's3m' => array(
			'audio/s3m',
			'audio/x-s3m',
		),
		's7m' => array(
			'application/x-sas-dmdb',
		),
		'sa7' => array(
			'application/x-sas-access',
		),
		'sac' => array(
			'application/tamp-sequence-adjust-confirm',
		),
		'saf' => array(
			'application/vnd.yamaha.smaf-audio',
		),
		'sam' => array(
			'application/x-amipro',
		),
		'sami' => array(
			'application/x-sami',
			'text/plain',
		),
		'sap' => array(
			'application/x-thomson-sap-image',
		),
		'sas' => array(
			'application/x-sas',
			'text/plain',
		),
		'sas7bacs' => array(
			'application/x-sas-access',
		),
		'sas7baud' => array(
			'application/x-sas-audit',
		),
		'sas7bbak' => array(
			'application/x-sas-backup',
		),
		'sas7bcat' => array(
			'application/x-sas-catalog',
		),
		'sas7bdat' => array(
			'application/x-sas-data',
		),
		'sas7bdmd' => array(
			'application/x-sas-dmdb',
		),
		'sas7bfdb' => array(
			'application/x-sas-fdb',
		),
		'sas7bitm' => array(
			'application/x-sas-itemstor',
		),
		'sas7bmdb' => array(
			'application/x-sas-mddb',
		),
		'sas7bndx' => array(
			'application/x-sas-data-index',
		),
		'sas7bpgm' => array(
			'application/x-sas-program-data',
		),
		'sas7bput' => array(
			'application/x-sas-putility',
		),
		'sas7butl' => array(
			'application/x-sas-utility',
		),
		'sas7bvew' => array(
			'application/x-sas-view',
		),
		'sass' => array(
			'text/plain',
			'text/x-sass',
		),
		'sav' => array(
			'application/x-spss-sav',
			'application/x-spss-savefile',
		),
		'sbml' => array(
			'application/sbml+xml',
		),
		'sc' => array(
			'application/vnd.ibm.secure-container',
		),
		'sc7' => array(
			'application/x-sas-catalog',
		),
		'scala' => array(
			'text/plain',
			'text/x-scala',
		),
		'scd' => array(
			'application/x-msschedule',
		),
		'scm' => array(
			'application/vnd.lotus-screencam',
			'text/plain',
			'text/x-scheme',
		),
		'scope' => array(
			'text/plain',
			'text/x-systemd-unit',
		),
		'scq' => array(
			'application/scvp-cv-request',
		),
		'scs' => array(
			'application/scvp-cv-response',
		),
		'scss' => array(
			'text/plain',
			'text/x-scss',
		),
		'scurl' => array(
			'text/vnd.curl.scurl',
		),
		'sd2' => array(
			'application/x-sas-data-v6',
		),
		'sd7' => array(
			'application/x-sas-data',
		),
		'sda' => array(
			'application/vnd.stardivision.draw',
		),
		'sdc' => array(
			'application/vnd.stardivision.calc',
		),
		'sdd' => array(
			'application/vnd.stardivision.impress',
		),
		'sdkd' => array(
			'application/vnd.solent.sdkm+xml',
		),
		'sdkm' => array(
			'application/vnd.solent.sdkm+xml',
		),
		'sdp' => array(
			'application/sdp',
			'application/vnd.sdp',
			'application/vnd.stardivision.impress',
			'application/x-sdp',
			'text/plain',
		),
		'sds' => array(
			'application/vnd.stardivision.chart',
		),
		'sdw' => array(
			'application/vnd.stardivision.writer',
			'application/vnd.stardivision.writer-global',
		),
		'sea' => array(
			'application/x-sea',
		),
		'sed' => array(
			'text/plain',
			'text/x-sed',
		),
		'see' => array(
			'application/vnd.seemail',
		),
		'seed' => array(
			'application/vnd.fdsn.seed',
		),
		'sema' => array(
			'application/vnd.sema',
		),
		'semd' => array(
			'application/vnd.semd',
		),
		'semf' => array(
			'application/vnd.semf',
		),
		'ser' => array(
			'application/java-serialized-object',
		),
		'service' => array(
			'text/plain',
			'text/x-dbus-service',
			'text/x-systemd-unit',
		),
		'setpay' => array(
			'application/set-payment-initiation',
		),
		'setreg' => array(
			'application/set-registration-initiation',
		),
		'sf7' => array(
			'application/x-sas-fdb',
		),
		'sfc' => array(
			'application/vnd.nintendo.snes.rom',
			'application/x-snes-rom',
		),
		'sfd-hdstx' => array(
			'application/vnd.hydrostatix.sof-data',
		),
		'sfdu' => array(
			'application/x-sfdu',
			'text/plain',
		),
		'sfs' => array(
			'application/vnd.spotfire.sfs',
		),
		'sfv' => array(
			'text/x-sfv',
		),
		'sg' => array(
			'application/x-sg1000-rom',
		),
		'sgb' => array(
			'application/x-gameboy-rom',
		),
		'sgf' => array(
			'application/x-go-sgf',
			'text/plain',
		),
		'sgi' => array(
			'image/sgi',
			'image/x-sgi',
		),
		'sgl' => array(
			'application/vnd.stardivision.writer',
			'application/vnd.stardivision.writer-global',
		),
		'sgm' => array(
			'text/plain',
			'text/sgml',
		),
		'sgml' => array(
			'text/plain',
			'text/sgml',
		),
		'sh' => array(
			'application/x-executable',
			'application/x-sh',
			'application/x-shellscript',
			'text/plain',
			'text/x-sh',
		),
		'shape' => array(
			'application/x-dia-shape',
			'application/xml',
		),
		'shar' => array(
			'application/x-shar',
		),
		'shf' => array(
			'application/shf+xml',
		),
		'shn' => array(
			'application/x-shorten',
			'audio/x-shorten',
		),
		'shp' => array(
			'application/x-shapefile',
		),
		'shtml' => array(
			'text/html',
		),
		'shw' => array(
			'application/x-corelpresentations',
		),
		'si7' => array(
			'application/x-sas-data-index',
		),
		'siag' => array(
			'application/x-siag',
		),
		'sid' => array(
			'audio/prs.sid',
			'image/x-mrsid-image',
		),
		'sig' => array(
			'application/pgp-signature',
			'text/plain',
		),
		'sik' => array(
			'application/x-trash',
		),
		'sil' => array(
			'audio/silk',
		),
		'silo' => array(
			'model/mesh',
		),
		'sis' => array(
			'application/vnd.symbian.install',
		),
		'sisx' => array(
			'application/vnd.symbian.install',
			'x-epoc/x-sisx-app',
		),
		'sit' => array(
			'application/stuffit',
			'application/x-sit',
			'application/x-stuffit',
		),
		'sitx' => array(
			'application/x-stuffitx',
		),
		'siv' => array(
			'application/sieve',
			'application/xml',
		),
		'sk' => array(
			'image/x-skencil',
		),
		'sk1' => array(
			'image/x-skencil',
		),
		'skd' => array(
			'application/vnd.koan',
			'application/x-koan',
		),
		'skm' => array(
			'application/vnd.koan',
			'application/x-koan',
		),
		'skp' => array(
			'application/vnd.koan',
			'application/x-koan',
		),
		'skr' => array(
			'application/pgp-keys',
			'text/plain',
		),
		'skt' => array(
			'application/vnd.koan',
			'application/x-koan',
		),
		'sldasm' => array(
			'application/sldworks',
		),
		'slddrw' => array(
			'application/sldworks',
		),
		'sldm' => array(
			'application/vnd.ms-powerpoint.slide.macroenabled.12',
			'application/vnd.openxmlformats-officedocument.presentationml.slide',
		),
		'sldprt' => array(
			'application/sldworks',
		),
		'sldx' => array(
			'application/vnd.openxmlformats-officedocument.presentationml.slide',
			'application/zip',
		),
		'slice' => array(
			'text/plain',
			'text/x-systemd-unit',
		),
		'slk' => array(
			'text/plain',
			'text/spreadsheet',
		),
		'slt' => array(
			'application/vnd.epson.salt',
		),
		'sm' => array(
			'application/vnd.stepmania.stepchart',
		),
		'sm7' => array(
			'application/x-sas-mddb',
		),
		'smaf' => array(
			'application/vnd.smaf',
			'application/x-smaf',
		),
		'smc' => array(
			'application/vnd.nintendo.snes.rom',
			'application/x-snes-rom',
		),
		'smd' => array(
			'application/vnd.stardivision.mail',
			'application/x-genesis-rom',
		),
		'smf' => array(
			'application/vnd.stardivision.math',
		),
		'smi' => array(
			'application/smil',
			'application/smil+xml',
			'application/x-sami',
			'application/xml',
			'text/plain',
		),
		'smil' => array(
			'application/smil',
			'application/smil+xml',
			'application/xml',
		),
		'sml' => array(
			'application/smil',
			'application/smil+xml',
			'application/xml',
		),
		'sms' => array(
			'application/x-sms-rom',
		),
		'smv' => array(
			'video/x-smv',
		),
		'smzip' => array(
			'application/vnd.stepmania.package',
		),
		'snap' => array(
			'application/vnd.snap',
			'application/vnd.squashfs',
		),
		'snd' => array(
			'audio/basic',
		),
		'snf' => array(
			'application/x-font-snf',
		),
		'so' => array(
			'application/octet-stream',
			'application/x-sharedlib',
		),
		'socket' => array(
			'text/plain',
			'text/x-systemd-unit',
		),
		'sp7' => array(
			'application/x-sas-putility',
		),
		'spc' => array(
			'application/x-pkcs7-certificates',
		),
		'spd' => array(
			'application/x-font-speedo',
		),
		'spec' => array(
			'text/plain',
			'text/x-rpm-spec',
		),
		'spf' => array(
			'application/vnd.yamaha.smaf-phrase',
		),
		'spl' => array(
			'application/futuresplash',
			'application/vnd.adobe.flash.movie',
			'application/x-futuresplash',
			'application/x-shockwave-flash',
		),
		'spm' => array(
			'application/x-rpm',
			'application/x-source-rpm',
		),
		'spot' => array(
			'text/vnd.in3d.spot',
		),
		'spp' => array(
			'application/scvp-vp-response',
		),
		'spq' => array(
			'application/scvp-vp-request',
		),
		'spx' => array(
			'application/x-speex',
			'audio/ogg',
			'audio/speex',
			'audio/x-speex',
		),
		'sql' => array(
			'application/sql',
			'application/x-sql',
			'text/plain',
			'text/x-sql',
		),
		'sqsh' => array(
			'application/vnd.squashfs',
		),
		'sr2' => array(
			'image/x-dcraw',
			'image/x-raw-sony',
			'image/x-sony-sr2',
		),
		'sr7' => array(
			'application/x-sas-itemstor',
		),
		'src' => array(
			'application/x-wais-source',
			'text/plain',
		),
		'srf' => array(
			'image/x-dcraw',
			'image/x-raw-sony',
			'image/x-sony-srf',
		),
		'srl' => array(
			'application/sereal',
		),
		'srt' => array(
			'application/x-srt',
			'application/x-subrip',
			'text/plain',
		),
		'sru' => array(
			'application/sru+xml',
		),
		'srx' => array(
			'application/sparql-results+xml',
		),
		'ss' => array(
			'text/plain',
			'text/x-scheme',
		),
		'ss7' => array(
			'application/x-sas-program-data',
		),
		'ssa' => array(
			'text/plain',
			'text/x-ssa',
		),
		'ssdl' => array(
			'application/ssdl+xml',
		),
		'sse' => array(
			'application/vnd.kodak-descriptor',
		),
		'ssf' => array(
			'application/vnd.epson.ssf',
		),
		'ssml' => array(
			'application/ssml+xml',
		),
		'st' => array(
			'application/vnd.sailingtracker.track',
			'text/plain',
			'text/x-stsrc',
		),
		'st7' => array(
			'application/x-sas-audit',
		),
		'stc' => array(
			'application/vnd.sun.xml.calc.template',
			'application/zip',
		),
		'std' => array(
			'application/vnd.sun.xml.draw.template',
			'application/zip',
		),
		'stf' => array(
			'application/vnd.wt.stf',
		),
		'sti' => array(
			'application/vnd.sun.xml.impress.template',
			'application/zip',
		),
		'stk' => array(
			'application/hyperstudio',
		),
		'stl' => array(
			'application/vnd.ms-pki.stl',
			'model/x.stl-ascii',
			'model/x.stl-binary',
			'text/plain',
		),
		'stm' => array(
			'audio/x-stm',
		),
		'str' => array(
			'application/vnd.pg.format',
		),
		'stw' => array(
			'application/vnd.sun.xml.writer.template',
			'application/zip',
		),
		'stx' => array(
			'application/x-sas-transport',
		),
		'sty' => array(
			'application/x-tex',
			'text/plain',
			'text/x-tex',
		),
		'su7' => array(
			'application/x-sas-utility',
		),
		'sub' => array(
			'image/vnd.dvb.subtitle',
			'text/plain',
			'text/vnd.dvb.subtitle',
			'text/x-microdvd',
			'text/x-mpsub',
			'text/x-subviewer',
		),
		'sun' => array(
			'image/x-sun-raster',
		),
		'sus' => array(
			'application/vnd.sus-calendar',
		),
		'susp' => array(
			'application/vnd.sus-calendar',
		),
		'sv' => array(
			'text/x-svsrc',
			'text/x-verilog',
		),
		'sv4cpio' => array(
			'application/x-sv4cpio',
		),
		'sv4crc' => array(
			'application/x-sv4crc',
		),
		'sv7' => array(
			'application/x-sas-view',
		),
		'svc' => array(
			'application/vnd.dvb.service',
		),
		'svd' => array(
			'application/vnd.svd',
		),
		'svg' => array(
			'application/xml',
			'image/svg+xml',
		),
		'svgz' => array(
			'application/gzip',
			'application/xml',
			'image/svg+xml',
			'image/svg+xml-compressed',
		),
		'svh' => array(
			'text/x-svhdr',
			'text/x-verilog',
		),
		'swa' => array(
			'application/x-director',
		),
		'swap' => array(
			'text/plain',
			'text/x-systemd-unit',
		),
		'swf' => array(
			'application/futuresplash',
			'application/vnd.adobe.flash.movie',
			'application/x-shockwave-flash',
		),
		'swi' => array(
			'application/vnd.arastra.swi',
			'application/vnd.aristanetworks.swi',
		),
		'swm' => array(
			'application/x-ms-wim',
		),
		'sxc' => array(
			'application/vnd.sun.xml.calc',
			'application/zip',
		),
		'sxd' => array(
			'application/vnd.sun.xml.draw',
			'application/zip',
		),
		'sxg' => array(
			'application/vnd.sun.xml.writer.global',
			'application/zip',
		),
		'sxi' => array(
			'application/vnd.sun.xml.impress',
			'application/zip',
		),
		'sxm' => array(
			'application/vnd.sun.xml.math',
			'application/zip',
		),
		'sxw' => array(
			'application/vnd.sun.xml.writer',
			'application/x-vnd.sun.xml.writer',
			'application/zip',
		),
		'sylk' => array(
			'text/plain',
			'text/spreadsheet',
		),
		'sz' => array(
			'application/x-snappy-framed',
		),
		't' => array(
			'text/troff',
		),
		't2t' => array(
			'text/plain',
			'text/x-txt2tags',
		),
		't3' => array(
			'application/x-t3vm-image',
		),
		't38' => array(
			'image/t38',
		),
		'taglet' => array(
			'application/vnd.mynfc',
		),
		'tam' => array(
			'application/vnd.onepager',
		),
		'tao' => array(
			'application/vnd.tao.intent-module-archive',
		),
		'tar' => array(
			'application/x-gtar',
			'application/x-tar',
		),
		'target' => array(
			'text/plain',
			'text/x-systemd-unit',
		),
		'tau' => array(
			'application/tamp-apex-update',
		),
		'taz' => array(
			'application/x-compress',
			'application/x-tarz',
		),
		'tb2' => array(
			'application/x-bzip',
			'application/x-bzip-compressed-tar',
		),
		'tbz' => array(
			'application/x-bzip',
			'application/x-bzip-compressed-tar',
		),
		'tbz2' => array(
			'application/x-bzip',
			'application/x-bzip-compressed-tar',
			'application/x-bzip2',
		),
		'tcap' => array(
			'application/vnd.3gpp2.tcap',
		),
		'tcl' => array(
			'application/x-tcl',
			'text/plain',
			'text/x-tcl',
		),
		'tcsh' => array(
			'application/x-csh',
		),
		'tcu' => array(
			'application/tamp-community-update',
		),
		'teacher' => array(
			'application/vnd.smart.teacher',
		),
		'tei' => array(
			'application/tei+xml',
		),
		'teicorpus' => array(
			'application/tei+xml',
		),
		'ter' => array(
			'application/tamp-error',
		),
		'tex' => array(
			'application/x-tex',
			'text/plain',
			'text/x-tex',
		),
		'texi' => array(
			'application/x-texinfo',
			'text/plain',
			'text/x-texinfo',
		),
		'texinfo' => array(
			'application/x-texinfo',
			'text/plain',
			'text/x-texinfo',
		),
		'text' => array(
			'text/plain',
		),
		'tfi' => array(
			'application/thraud+xml',
		),
		'tfm' => array(
			'application/x-tex-tfm',
		),
		'tfx' => array(
			'image/tiff-fx',
		),
		'tga' => array(
			'image/x-icb',
			'image/x-tga',
		),
		'tgz' => array(
			'application/gzip',
			'application/gzip-compressed',
			'application/gzipped',
			'application/x-compressed-tar',
			'application/x-gunzip',
			'application/x-gzip',
			'application/x-gzip-compressed',
			'gzip/document',
		),
		'the' => array(
			'message/global-headers',
		),
		'theme' => array(
			'application/x-desktop',
			'application/x-theme',
		),
		'themepack' => array(
			'application/vnd.ms-cab-compressed',
			'application/x-windows-themepack',
		),
		'thmx' => array(
			'application/vnd.ms-officetheme',
			'application/vnd.openxmlformats-officedocument.presentationml.presentation',
		),
		'tif' => array(
			'image/tiff',
		),
		'tiff' => array(
			'image/tiff',
		),
		'timer' => array(
			'text/plain',
			'text/x-systemd-unit',
		),
		'tk' => array(
			'application/x-tcl',
			'text/plain',
			'text/x-tcl',
		),
		'tld' => array(
			'text/plain',
		),
		'tlrz' => array(
			'application/x-lrzip',
			'application/x-lrzip-compressed-tar',
		),
		'tlz' => array(
			'application/x-lzma',
			'application/x-lzma-compressed-tar',
		),
		'tmo' => array(
			'application/vnd.tmobile-livetv',
		),
		'tnef' => array(
			'application/ms-tnef',
			'application/vnd.ms-tnef',
		),
		'tnf' => array(
			'application/ms-tnef',
			'application/vnd.ms-tnef',
		),
		'toast' => array(
			'application/x-iso9660-image',
			'application/x-roxio-toast',
		),
		'toc' => array(
			'application/x-cdrdao-toc',
			'text/plain',
		),
		'torrent' => array(
			'application/x-bittorrent',
		),
		'tpic' => array(
			'image/x-icb',
			'image/x-tga',
		),
		'tpl' => array(
			'application/vnd.groove-tool-template',
		),
		'tpt' => array(
			'application/vnd.trid.tpt',
		),
		'tr' => array(
			'application/x-troff',
			'application/x-troff-man',
			'application/x-troff-me',
			'application/x-troff-ms',
			'text/plain',
			'text/troff',
			'text/x-troff',
		),
		'tra' => array(
			'application/vnd.trueapp',
		),
		'trig' => array(
			'application/trig',
			'application/x-trig',
			'text/plain',
		),
		'trm' => array(
			'application/x-msterminal',
		),
		'ts' => array(
			'application/x-linguist',
			'application/xml',
			'text/vnd.trolltech.linguist',
			'video/mp2t',
		),
		'tsa' => array(
			'application/tamp-sequence-adjust',
		),
		'tsd' => array(
			'application/timestamped-data',
		),
		'tsq' => array(
			'application/tamp-status-query',
		),
		'tsr' => array(
			'application/tamp-status-response',
		),
		'tsv' => array(
			'text/plain',
			'text/tab-separated-values',
		),
		'tta' => array(
			'audio/tta',
			'audio/x-tta',
		),
		'ttc' => array(
			'application/x-font-ttf',
			'font/collection',
		),
		'ttf' => array(
			'application/x-font-ttf',
			'font/ttf',
		),
		'ttl' => array(
			'text/plain',
			'text/turtle',
		),
		'ttml' => array(
			'application/ttml+xml',
		),
		'ttx' => array(
			'application/x-font-ttx',
			'application/xml',
		),
		'tuc' => array(
			'application/tamp-update-confirm',
		),
		'tur' => array(
			'application/tamp-update',
		),
		'twd' => array(
			'application/vnd.simtech-mindmapper',
		),
		'twds' => array(
			'application/vnd.simtech-mindmapper',
		),
		'twig' => array(
			'text/plain',
			'text/x-twig',
		),
		'txd' => array(
			'application/vnd.genomatix.tuxedo',
		),
		'txf' => array(
			'application/vnd.mobius.txf',
		),
		'txt' => array(
			'text/plain',
			'text/prs.prop.logic',
		),
		'txz' => array(
			'application/x-xz',
			'application/x-xz-compressed-tar',
		),
		'types' => array(
			'text/plain',
		),
		'tzo' => array(
			'application/x-lzop',
			'application/x-tzo',
		),
		'u32' => array(
			'application/x-authorware-bin',
		),
		'uc2' => array(
			'application/x-uc2-compressed',
		),
		'udeb' => array(
			'application/vnd.debian.binary-package',
			'application/x-archive',
			'application/x-deb',
			'application/x-debian-package',
		),
		'ufd' => array(
			'application/vnd.ufdl',
		),
		'ufdl' => array(
			'application/vnd.ufdl',
		),
		'ufraw' => array(
			'application/x-ufraw',
			'application/xml',
		),
		'ui' => array(
			'application/x-designer',
			'application/x-gtk-builder',
			'application/xml',
		),
		'uil' => array(
			'text/plain',
			'text/x-uil',
		),
		'ult' => array(
			'audio/x-mod',
		),
		'ulx' => array(
			'application/x-glulx',
		),
		'umj' => array(
			'application/vnd.umajin',
		),
		'unf' => array(
			'application/x-nes-rom',
		),
		'uni' => array(
			'audio/x-mod',
		),
		'unif' => array(
			'application/x-nes-rom',
		),
		'unityweb' => array(
			'application/vnd.unity',
		),
		'unknown' => array(
			'application/dns',
		),
		'uo' => array(
			'application/vnd.uoml+xml',
		),
		'uoml' => array(
			'application/vnd.uoml+xml',
		),
		'uri' => array(
			'text/uri-list',
		),
		'uric' => array(
			'text/vnd.si.uricatalogue',
		),
		'uris' => array(
			'text/uri-list',
		),
		'url' => array(
			'application/x-mswinurl',
		),
		'urls' => array(
			'text/uri-list',
		),
		'ustar' => array(
			'application/x-ustar',
		),
		'utz' => array(
			'application/vnd.uiq.theme',
		),
		'uu' => array(
			'text/x-uuencode',
		),
		'uue' => array(
			'text/plain',
			'text/x-uuencode',
			'zz-application/zz-winassoc-uu',
		),
		'uva' => array(
			'audio/vnd.dece.audio',
		),
		'uvd' => array(
			'application/vnd.dece.data',
		),
		'uvf' => array(
			'application/vnd.dece.data',
		),
		'uvg' => array(
			'image/vnd.dece.graphic',
		),
		'uvh' => array(
			'video/vnd.dece.hd',
		),
		'uvi' => array(
			'image/vnd.dece.graphic',
		),
		'uvm' => array(
			'video/vnd.dece.mobile',
		),
		'uvp' => array(
			'video/vnd.dece.pd',
		),
		'uvs' => array(
			'video/vnd.dece.sd',
		),
		'uvt' => array(
			'application/vnd.dece.ttml+xml',
		),
		'uvu' => array(
			'video/vnd.uvvu.mp4',
		),
		'uvv' => array(
			'video/vnd.dece.video',
		),
		'uvva' => array(
			'audio/vnd.dece.audio',
		),
		'uvvd' => array(
			'application/vnd.dece.data',
		),
		'uvvf' => array(
			'application/vnd.dece.data',
		),
		'uvvg' => array(
			'image/vnd.dece.graphic',
		),
		'uvvh' => array(
			'video/vnd.dece.hd',
		),
		'uvvi' => array(
			'image/vnd.dece.graphic',
		),
		'uvvm' => array(
			'video/vnd.dece.mobile',
		),
		'uvvp' => array(
			'video/vnd.dece.pd',
		),
		'uvvs' => array(
			'video/vnd.dece.sd',
		),
		'uvvt' => array(
			'application/vnd.dece.ttml+xml',
		),
		'uvvu' => array(
			'video/vnd.uvvu.mp4',
		),
		'uvvv' => array(
			'video/vnd.dece.video',
		),
		'uvvx' => array(
			'application/vnd.dece.unspecified',
		),
		'uvvz' => array(
			'application/vnd.dece.zip',
		),
		'uvx' => array(
			'application/vnd.dece.unspecified',
		),
		'uvz' => array(
			'application/vnd.dece.zip',
		),
		'v64' => array(
			'application/x-n64-rom',
		),
		'vala' => array(
			'text/x-csrc',
			'text/x-vala',
		),
		'vapi' => array(
			'text/x-csrc',
			'text/x-vala',
		),
		'vb' => array(
			'text/x-vbasic',
			'text/x-vbdotnet',
		),
		'vbs' => array(
			'text/x-vbasic',
			'text/x-vbscript',
		),
		'vcard' => array(
			'text/directory',
			'text/plain',
			'text/vcard',
			'text/x-vcard',
		),
		'vcd' => array(
			'application/x-cdlink',
		),
		'vcf' => array(
			'text/directory',
			'text/plain',
			'text/vcard',
			'text/x-vcard',
		),
		'vcg' => array(
			'application/vnd.groove-vcard',
		),
		'vcs' => array(
			'application/ics',
			'text/calendar',
			'text/plain',
			'text/x-vcalendar',
		),
		'vct' => array(
			'text/directory',
			'text/plain',
			'text/vcard',
			'text/x-vcard',
		),
		'vcx' => array(
			'application/vnd.vcx',
		),
		'vda' => array(
			'image/x-icb',
			'image/x-tga',
		),
		'vhd' => array(
			'text/plain',
			'text/x-vhdl',
		),
		'vhdl' => array(
			'text/plain',
			'text/x-vhdl',
		),
		'vis' => array(
			'application/vnd.visionary',
		),
		'viv' => array(
			'video/vivo',
			'video/vnd.vivo',
		),
		'vivo' => array(
			'video/vivo',
			'video/vnd.vivo',
		),
		'vlc' => array(
			'application/m3u',
			'audio/m3u',
			'audio/mpegurl',
			'audio/x-m3u',
			'audio/x-mp3-playlist',
			'audio/x-mpegurl',
			'text/plain',
		),
		'vm' => array(
			'text/plain',
		),
		'vmdk' => array(
			'application/x-vmdk',
		),
		'vob' => array(
			'video/mpeg',
			'video/mpeg-system',
			'video/x-mpeg',
			'video/x-mpeg-system',
			'video/x-mpeg2',
			'video/x-ms-vob',
		),
		'voc' => array(
			'audio/x-voc',
		),
		'vor' => array(
			'application/vnd.stardivision.writer',
			'application/vnd.stardivision.writer-global',
			'application/x-staroffice-template',
		),
		'vox' => array(
			'application/x-authorware-bin',
		),
		'vpm' => array(
			'multipart/voice-message',
		),
		'vrm' => array(
			'model/vrml',
			'text/plain',
		),
		'vrml' => array(
			'model/vrml',
			'text/plain',
		),
		'vsd' => array(
			'application/vnd.ms-visio',
			'application/vnd.visio',
			'application/x-ole-storage',
		),
		'vsdm' => array(
			'application/vnd.ms-visio.drawing.macroenabled.12',
			'application/vnd.ms-visio.drawing.macroenabled.main+xml',
			'application/zip',
		),
		'vsdx' => array(
			'application/vnd.ms-visio.drawing',
			'application/vnd.ms-visio.drawing.main+xml',
			'application/zip',
		),
		'vsf' => array(
			'application/vnd.vsf',
		),
		'vsl' => array(
			'text/plain',
		),
		'vss' => array(
			'application/vnd.ms-visio',
			'application/vnd.visio',
			'application/x-ole-storage',
		),
		'vssm' => array(
			'application/vnd.ms-visio.stencil.macroenabled.12',
			'application/vnd.ms-visio.stencil.macroenabled.main+xml',
			'application/zip',
		),
		'vssx' => array(
			'application/vnd.ms-visio.stencil',
			'application/vnd.ms-visio.stencil.main+xml',
			'application/zip',
		),
		'vst' => array(
			'application/vnd.ms-visio',
			'application/vnd.visio',
			'application/x-ole-storage',
			'image/x-icb',
			'image/x-tga',
		),
		'vstm' => array(
			'application/vnd.ms-visio.template.macroenabled.12',
			'application/vnd.ms-visio.template.macroenabled.main+xml',
			'application/zip',
		),
		'vstx' => array(
			'application/vnd.ms-visio.template',
			'application/vnd.ms-visio.template.main+xml',
			'application/zip',
		),
		'vsw' => array(
			'application/vnd.ms-visio',
			'application/vnd.visio',
			'application/x-ole-storage',
		),
		'vtt' => array(
			'text/plain',
			'text/vtt',
		),
		'vtu' => array(
			'model/vnd.vtu',
		),
		'vwx' => array(
			'application/vnd.vectorworks',
		),
		'vxml' => array(
			'application/voicexml+xml',
		),
		'w3d' => array(
			'application/x-director',
		),
		'w60' => array(
			'application/vnd.wordperfect',
		),
		'wad' => array(
			'application/x-doom',
			'application/x-doom-wad',
			'application/x-wii-wad',
		),
		'war' => array(
			'application/java-archive',
			'application/x-tika-java-web-archive',
		),
		'wav' => array(
			'audio/vnd.wave',
			'audio/wav',
			'audio/x-wav',
		),
		'wax' => array(
			'application/x-ms-asx',
			'audio/x-ms-asx',
			'audio/x-ms-wax',
			'video/x-ms-wax',
			'video/x-ms-wmx',
			'video/x-ms-wvx',
		),
		'wb1' => array(
			'application/x-quattro-pro',
			'application/x-quattropro',
		),
		'wb2' => array(
			'application/x-quattro-pro',
			'application/x-quattropro',
		),
		'wb3' => array(
			'application/x-quattro-pro',
			'application/x-quattropro',
		),
		'wbmp' => array(
			'image/vnd-wap-wbmp',
			'image/vnd.wap.wbmp',
		),
		'wbs' => array(
			'application/vnd.criticaltools.wbs+xml',
		),
		'wbxml' => array(
			'application/vnd.wap-wbxml',
			'application/vnd.wap.wbxml',
		),
		'wcm' => array(
			'application/vnd.ms-works',
			'application/x-ole-storage',
		),
		'wdb' => array(
			'application/vnd.ms-works',
			'application/x-ole-storage',
		),
		'wdp' => array(
			'image/vnd.ms-photo',
		),
		'weba' => array(
			'audio/webm',
		),
		'webarchive' => array(
			'application/x-bplist',
			'application/x-webarchive',
		),
		'webm' => array(
			'application/x-matroska',
			'video/webm',
		),
		'webp' => array(
			'image/webp',
		),
		'wg' => array(
			'application/vnd.pmi.widget',
		),
		'wgt' => array(
			'application/widget',
		),
		'wif' => array(
			'application/watcherinfo+xml',
		),
		'wim' => array(
			'application/x-ms-wim',
		),
		'wk1' => array(
			'application/lotus123',
			'application/vnd.lotus-1-2-3',
			'application/wk1',
			'application/x-123',
			'application/x-lotus123',
			'zz-application/zz-winassoc-123',
		),
		'wk3' => array(
			'application/lotus123',
			'application/vnd.lotus-1-2-3',
			'application/wk1',
			'application/x-123',
			'application/x-lotus123',
			'zz-application/zz-winassoc-123',
		),
		'wk4' => array(
			'application/lotus123',
			'application/vnd.lotus-1-2-3',
			'application/wk1',
			'application/x-123',
			'application/x-lotus123',
			'zz-application/zz-winassoc-123',
		),
		'wkdownload' => array(
			'application/x-partial-download',
		),
		'wks' => array(
			'application/lotus123',
			'application/vnd.lotus-1-2-3',
			'application/vnd.ms-works',
			'application/wk1',
			'application/x-123',
			'application/x-lotus123',
			'application/x-ole-storage',
			'zz-application/zz-winassoc-123',
		),
		'wm' => array(
			'video/x-ms-wm',
		),
		'wma' => array(
			'application/vnd.ms-asf',
			'audio/wma',
			'audio/x-ms-wma',
			'video/x-ms-asf',
		),
		'wmd' => array(
			'application/x-ms-wmd',
		),
		'wmf' => array(
			'application/wmf',
			'application/x-msmetafile',
			'application/x-wmf',
			'image/wmf',
			'image/x-win-metafile',
			'image/x-wmf',
		),
		'wml' => array(
			'application/xml',
			'text/vnd.wap.wml',
		),
		'wmlc' => array(
			'application/vnd.wap.wmlc',
		),
		'wmls' => array(
			'text/vnd.wap.wmlscript',
		),
		'wmlsc' => array(
			'application/vnd.wap.wmlscriptc',
		),
		'wmv' => array(
			'application/vnd.ms-asf',
			'video/x-ms-asf',
			'video/x-ms-wmv',
		),
		'wmx' => array(
			'application/x-ms-asx',
			'audio/x-ms-asx',
			'video/x-ms-wax',
			'video/x-ms-wmx',
			'video/x-ms-wvx',
		),
		'wmz' => array(
			'application/x-gzip',
			'application/x-ms-wmz',
			'application/x-msmetafile',
		),
		'woff' => array(
			'application/font-woff',
			'font/woff',
		),
		'woff2' => array(
			'font/woff',
			'font/woff2',
		),
		'wp' => array(
			'application/vnd.wordperfect',
			'application/wordperfect',
			'application/x-wordperfect',
		),
		'wp4' => array(
			'application/vnd.wordperfect',
			'application/wordperfect',
			'application/x-wordperfect',
		),
		'wp5' => array(
			'application/vnd.wordperfect',
			'application/wordperfect',
			'application/x-wordperfect',
		),
		'wp6' => array(
			'application/vnd.wordperfect',
			'application/wordperfect',
			'application/x-wordperfect',
		),
		'wp61' => array(
			'application/vnd.wordperfect',
		),
		'wpd' => array(
			'application/vnd.wordperfect',
			'application/wordperfect',
			'application/x-wordperfect',
		),
		'wpg' => array(
			'application/x-wpg',
		),
		'wpl' => array(
			'application/vnd.ms-wpl',
		),
		'wpp' => array(
			'application/vnd.wordperfect',
			'application/wordperfect',
			'application/x-wordperfect',
		),
		'wps' => array(
			'application/vnd.ms-works',
			'application/x-ole-storage',
		),
		'wpt' => array(
			'application/vnd.wordperfect',
		),
		'wqd' => array(
			'application/vnd.wqd',
		),
		'wri' => array(
			'application/x-mswrite',
		),
		'wrl' => array(
			'model/vrml',
			'text/plain',
		),
		'wsdd' => array(
			'text/plain',
		),
		'wsdl' => array(
			'application/wsdl+xml',
		),
		'wsgi' => array(
			'application/x-executable',
			'text/x-python',
		),
		'wspolicy' => array(
			'application/wspolicy+xml',
		),
		'wtb' => array(
			'application/vnd.webturbo',
		),
		'wv' => array(
			'audio/x-wavpack',
		),
		'wvc' => array(
			'audio/x-wavpack-correction',
		),
		'wvp' => array(
			'audio/x-wavpack',
		),
		'wvx' => array(
			'application/x-ms-asx',
			'audio/x-ms-asx',
			'video/x-ms-wax',
			'video/x-ms-wmx',
			'video/x-ms-wvx',
		),
		'wwf' => array(
			'application/pdf',
			'application/wwf',
			'application/x-wwf',
		),
		'x32' => array(
			'application/x-authorware-bin',
		),
		'x3d' => array(
			'application/vnd.hzn-3d-crossword',
			'model/x3d+xml',
		),
		'x3db' => array(
			'model/x3d+binary',
		),
		'x3dbz' => array(
			'model/x3d+binary',
		),
		'x3dv' => array(
			'model/x3d+vrml',
		),
		'x3dvz' => array(
			'model/x3d+vrml',
		),
		'x3dz' => array(
			'model/x3d+xml',
		),
		'x3f' => array(
			'image/x-dcraw',
			'image/x-raw-sigma',
			'image/x-sigma-x3f',
		),
		'xac' => array(
			'application/x-gnucash',
		),
		'xaml' => array(
			'application/xaml+xml',
		),
		'xap' => array(
			'application/x-silverlight-app',
		),
		'xar' => array(
			'application/vnd.xara',
			'application/x-xar',
		),
		'xargs' => array(
			'text/plain',
		),
		'xav' => array(
			'application/xcap-att+xml',
		),
		'xbap' => array(
			'application/x-ms-xbap',
		),
		'xbd' => array(
			'application/vnd.fujixerox.docuworks.binder',
		),
		'xbel' => array(
			'application/x-xbel',
			'application/xml',
		),
		'xbl' => array(
			'application/xml',
			'text/plain',
			'text/xml',
		),
		'xbm' => array(
			'image/x-xbitmap',
			'text/x-c',
		),
		'xca' => array(
			'application/xcap-caps+xml',
		),
		'xcat' => array(
			'text/plain',
		),
		'xcf' => array(
			'image/x-xcf',
			'image/xcf',
		),
		'xconf' => array(
			'text/plain',
		),
		'xcs' => array(
			'application/calendar+xml',
		),
		'xdf' => array(
			'application/mrb-consumer+xml',
			'application/mrb-publish+xml',
			'application/xcap-diff+xml',
		),
		'xdgapp' => array(
			'application/vnd.flatpak',
			'application/vnd.xdgapp',
		),
		'xdm' => array(
			'application/vnd.syncml.dm+xml',
		),
		'xdp' => array(
			'application/vnd.adobe.xdp+xml',
		),
		'xdssc' => array(
			'application/dssc+xml',
		),
		'xdw' => array(
			'application/vnd.fujixerox.docuworks',
		),
		'xegrm' => array(
			'text/plain',
		),
		'xel' => array(
			'application/xcap-el+xml',
		),
		'xenc' => array(
			'application/xenc+xml',
		),
		'xer' => array(
			'application/patch-ops-error+xml',
			'application/xcap-error+xml',
		),
		'xfdf' => array(
			'application/vnd.adobe.xfdf',
		),
		'xfdl' => array(
			'application/vnd.xfdl',
		),
		'xgrm' => array(
			'text/plain',
		),
		'xht' => array(
			'application/xhtml+xml',
			'application/xml',
		),
		'xhtml' => array(
			'application/xhtml+xml',
			'application/xml',
		),
		'xhvml' => array(
			'application/xv+xml',
		),
		'xi' => array(
			'audio/x-xi',
		),
		'xif' => array(
			'image/vnd.xiff',
		),
		'xla' => array(
			'application/msexcel',
			'application/vnd.ms-excel',
			'application/vnd.ms-office',
			'application/x-msexcel',
			'application/xml',
			'zz-application/zz-winassoc-xls',
		),
		'xlam' => array(
			'application/vnd.ms-excel.addin.macroenabled.12',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		),
		'xlc' => array(
			'application/msexcel',
			'application/vnd.ms-excel',
			'application/vnd.ms-office',
			'application/x-msexcel',
			'application/xml',
			'zz-application/zz-winassoc-xls',
		),
		'xld' => array(
			'application/msexcel',
			'application/vnd.ms-excel',
			'application/vnd.ms-office',
			'application/x-msexcel',
			'application/xml',
			'zz-application/zz-winassoc-xls',
		),
		'xlex' => array(
			'text/plain',
		),
		'xlf' => array(
			'application/x-xliff',
			'application/x-xliff+xml',
			'application/xml',
		),
		'xliff' => array(
			'application/x-xliff',
			'application/xml',
		),
		'xll' => array(
			'application/msexcel',
			'application/vnd.ms-excel',
			'application/vnd.ms-office',
			'application/x-msexcel',
			'application/xml',
			'zz-application/zz-winassoc-xls',
		),
		'xlm' => array(
			'application/msexcel',
			'application/vnd.ms-excel',
			'application/vnd.ms-office',
			'application/x-msexcel',
			'application/xml',
			'zz-application/zz-winassoc-xls',
		),
		'xlog' => array(
			'text/plain',
		),
		'xlr' => array(
			'application/vnd.ms-excel',
			'application/vnd.ms-office',
			'application/vnd.ms-works',
			'application/x-ole-storage',
			'application/x-tika-msworks-spreadsheet',
			'application/xml',
		),
		'xls' => array(
			'application/msexcel',
			'application/vnd.ms-excel',
			'application/vnd.ms-office',
			'application/x-msexcel',
			'application/xml',
			'zz-application/zz-winassoc-xls',
		),
		'xlsb' => array(
			'application/vnd.ms-excel.sheet.binary.macroenabled.12',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		),
		'xlsm' => array(
			'application/vnd.ms-excel.sheet.macroenabled.12',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		),
		'xlsx' => array(
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'application/zip',
		),
		'xlt' => array(
			'application/msexcel',
			'application/vnd.ms-excel',
			'application/vnd.ms-office',
			'application/x-msexcel',
			'application/xml',
			'zz-application/zz-winassoc-xls',
		),
		'xltm' => array(
			'application/vnd.ms-excel.template.macroenabled.12',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
		),
		'xltx' => array(
			'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
			'application/zip',
		),
		'xlw' => array(
			'application/msexcel',
			'application/vnd.ms-excel',
			'application/vnd.ms-office',
			'application/x-msexcel',
			'application/xml',
			'zz-application/zz-winassoc-xls',
		),
		'xm' => array(
			'audio/x-xm',
			'audio/xm',
		),
		'xmap' => array(
			'text/plain',
		),
		'xmf' => array(
			'audio/mobile-xmf',
			'audio/x-xmf',
			'audio/xmf',
		),
		'xmi' => array(
			'application/xml',
			'text/x-xmi',
		),
		'xmind' => array(
			'application/x-xmind',
			'application/zip',
		),
		'xml' => array(
			'application/cea-2018+xml',
			'application/conference-info+xml',
			'application/cpl+xml',
			'application/dialog-info+xml',
			'application/dicom+xml',
			'application/emergencycalldata.comment+xml',
			'application/emergencycalldata.control+xml',
			'application/emergencycalldata.deviceinfo+xml',
			'application/emergencycalldata.providerinfo+xml',
			'application/emergencycalldata.serviceinfo+xml',
			'application/emergencycalldata.subscriberinfo+xml',
			'application/emergencycalldata.veds+xml',
			'application/epp+xml',
			'application/load-control+xml',
			'application/media-policy-dataset+xml',
			'application/pidf-diff+xml',
			'application/reginfo+xml',
			'application/rfc+xml',
			'application/simple-filter+xml',
			'application/vnd.iptc.g2.conceptitem+xml',
			'application/vnd.iptc.g2.knowledgeitem+xml',
			'application/vnd.iptc.g2.newsitem+xml',
			'application/vnd.iptc.g2.newsmessage+xml',
			'application/vnd.iptc.g2.packageitem+xml',
			'application/vnd.iptc.g2.planningitem+xml',
			'application/vnd.recordare.musicxml+xml',
			'application/watcherinfo+xml',
			'application/x-xml',
			'application/xcon-conference-info+xml',
			'application/xcon-conference-info-diff+xml',
			'application/xenc+xml',
			'application/xml',
			'application/xml-external-parsed-entity',
			'message/imdn+xml',
			'text/plain',
			'text/xml',
			'text/xml-external-parsed-entity',
		),
		'xmls' => array(
			'application/dskpp+xml',
		),
		'xmp' => array(
			'application/rdf+xml',
			'application/xml',
		),
		'xns' => array(
			'application/xcap-ns+xml',
		),
		'xo' => array(
			'application/vnd.olpc-sugar',
		),
		'xop' => array(
			'application/xop+xml',
		),
		'xpi' => array(
			'application/x-xpinstall',
			'application/zip',
		),
		'xpl' => array(
			'application/xproc+xml',
		),
		'xpm' => array(
			'image/x-xpixmap',
			'image/x-xpm',
		),
		'xport' => array(
			'application/x-sas-xport',
		),
		'xpr' => array(
			'application/vnd.is-xpr',
		),
		'xps' => array(
			'application/oxps',
			'application/vnd.ms-xpsdocument',
			'application/zip',
		),
		'xpt' => array(
			'application/x-sas-xport',
		),
		'xpw' => array(
			'application/vnd.intercon.formnet',
		),
		'xpx' => array(
			'application/vnd.intercon.formnet',
		),
		'xq' => array(
			'application/xquery',
			'text/plain',
		),
		'xquery' => array(
			'application/xquery',
			'text/plain',
		),
		'xroles' => array(
			'text/plain',
		),
		'xsamples' => array(
			'text/plain',
		),
		'xsd' => array(
			'application/x-xml',
			'application/xml',
			'text/plain',
			'text/xml',
		),
		'xsl' => array(
			'application/x-xml',
			'application/xml',
			'application/xslt+xml',
			'text/plain',
			'text/xml',
		),
		'xslfo' => array(
			'application/xml',
			'application/xslfo+xml',
			'text/x-xslfo',
			'text/xsl',
		),
		'xslt' => array(
			'application/xml',
			'application/xslt+xml',
			'text/xsl',
		),
		'xsm' => array(
			'application/vnd.syncml+xml',
		),
		'xsp' => array(
			'text/plain',
		),
		'xspf' => array(
			'application/x-xspf+xml',
			'application/xml',
			'application/xspf+xml',
		),
		'xul' => array(
			'application/vnd.mozilla.xul+xml',
			'application/xml',
		),
		'xvm' => array(
			'application/xv+xml',
		),
		'xvml' => array(
			'application/xv+xml',
		),
		'xwd' => array(
			'image/x-xwindowdump',
		),
		'xweb' => array(
			'text/plain',
		),
		'xwelcome' => array(
			'text/plain',
		),
		'xyz' => array(
			'chemical/x-xyz',
		),
		'xyze' => array(
			'image/vnd.radiance',
		),
		'xz' => array(
			'application/x-xz',
		),
		'yaml' => array(
			'application/x-yaml',
			'text/plain',
			'text/x-yaml',
			'text/yaml',
		),
		'yang' => array(
			'application/yang',
		),
		'yin' => array(
			'application/yin+xml',
		),
		'yml' => array(
			'application/x-yaml',
			'text/plain',
			'text/x-yaml',
			'text/yaml',
		),
		'z1' => array(
			'application/x-zmachine',
		),
		'z2' => array(
			'application/x-zmachine',
		),
		'z3' => array(
			'application/x-zmachine',
		),
		'z4' => array(
			'application/x-zmachine',
		),
		'z5' => array(
			'application/x-zmachine',
		),
		'z6' => array(
			'application/x-zmachine',
		),
		'z64' => array(
			'application/x-n64-rom',
		),
		'z7' => array(
			'application/x-zmachine',
		),
		'z8' => array(
			'application/x-zmachine',
		),
		'zabw' => array(
			'application/x-abiword',
			'application/xml',
		),
		'zaz' => array(
			'application/vnd.zzazz.deck+xml',
		),
		'zfc' => array(
			'application/vnd.filmit.zfc',
		),
		'zfo' => array(
			'application/vnd.software602.filler.form-xml-zip',
		),
		'zip' => array(
			'application/x-zip',
			'application/x-zip-compressed',
			'application/zip',
		),
		'zir' => array(
			'application/vnd.zul',
		),
		'zirz' => array(
			'application/vnd.zul',
		),
		'zmm' => array(
			'application/vnd.handheld-entertainment+xml',
		),
		'zoo' => array(
			'application/x-zoo',
		),
		'zsav' => array(
			'application/x-spss-sav',
			'application/x-spss-savefile',
		),
		'zz' => array(
			'application/zlib',
		),
		'123' => array(
			'application/lotus123',
			'application/vnd.lotus-1-2-3',
			'application/wk1',
			'application/x-123',
			'application/x-lotus123',
			'zz-application/zz-winassoc-123',
		),
		'602' => array(
			'application/x-t602',
		),
		'669' => array(
			'audio/x-mod',
		),
	);

	$ext = trim( strtolower( $ext ) );
	$ext = ltrim( $ext, '.' );
	if ( strlen( $ext ) && isset( $mimes[ $ext ] ) ) {
		$match = $mimes[ $ext ];
	} else {
		$match = false;
	}

	/**
	 * Filters the MIME alias list.
	 *
	 * @since 0.1.0
	 *
	 * @param array|bool $match The aliases found. False on failure.
	 * @param string $ext The file extension.
	 */
	return apply_filters( 'wp_get_mime_aliases', $match, $ext );
}
