<?php
class WeeklyModel
{
	
	private $document_string;
	private $head;
	private $local_path = '/alidata1/weekly';
	private $docxml_path = '';
	private $zip_path = '';
	private $template_path = '/alidata1/weekly/template';
	private $docx_path = '/alidata1/weekly/docx';
	
	public function WeeklyModel($username)
	{
		$this->document_string = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><w:document xmlns:wpc="http://schemas.microsoft.com/office/word/2010/wordprocessingCanvas" xmlns:mc="http://schemas.openxmlformats.org/markup-compatibility/2006" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships" xmlns:m="http://schemas.openxmlformats.org/officeDocument/2006/math" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:wp14="http://schemas.microsoft.com/office/word/2010/wordprocessingDrawing" xmlns:wp="http://schemas.openxmlformats.org/drawingml/2006/wordprocessingDrawing" xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main" xmlns:w14="http://schemas.microsoft.com/office/word/2010/wordml" xmlns:w10="urn:schemas-microsoft-com:office:word" xmlns:wpg="http://schemas.microsoft.com/office/word/2010/wordprocessingGroup" xmlns:wpi="http://schemas.microsoft.com/office/word/2010/wordprocessingInk" xmlns:wne="http://schemas.microsoft.com/office/word/2006/wordml" xmlns:wps="http://schemas.microsoft.com/office/word/2010/wordprocessingShape" mc:Ignorable="w14 wp14"><w:body>';
		
		//create private weekly folder
		$dirname = md5($username);
		if ( ! file_exists($this->template_path . '/' . $dirname) ) {
			exec("cp -r {$this->template_path}/standard/ {$this->template_path}/{$dirname}");
		}
		$this->docxml_path = $this->template_path . '/' . $dirname . '/word/document.xml';
		$this->zip_path = $this->template_path . '/' . $dirname;
		$this->docx_path = $this->docx_path . '/' . $dirname;
		if ( !file_exists($this->docx_path) ) {
			exec("mkdir {$this->docx_path}");
		}
	}
	
	public function setHead($head)
	{
		$head_string = "
		<w:p>
			<w:pPr>
				<w:jc w:val=\"center\"/>
				<w:rPr>
					<w:rFonts w:hint=\"eastAsia\" w:ascii=\"宋体\" w:hAnsi=\"宋体\"/>
					<w:b/>
					<w:sz w:val=\"28\"/>
					<w:szCs w:val=\"28\"/>
				</w:rPr>
			</w:pPr>
			<w:r>
				<w:rPr>
					<w:rFonts w:hint=\"eastAsia\" w:ascii=\"宋体\" w:hAnsi=\"宋体\"/>
					<w:b/>
					<w:sz w:val=\"28\"/>
					<w:szCs w:val=\"28\"/>
				</w:rPr>
				<w:t>{$head}</w:t>
			</w:r>
		</w:p>";
				
		$this->document_string .= $head_string;
		$this->head = $head;
	}
	
	public function setTitle($title)
	{
		$title_string = "
		<w:p>
			<w:pPr>
				<w:pStyle w:val=\"8\"/>
				<w:numPr>
					<w:ilvl w:val=\"0\"/>
					<w:numId w:val=\"1\"/>
				</w:numPr>
				<w:ind w:firstLineChars=\"0\"/>
				<w:rPr>
					<w:rFonts w:hint=\"eastAsia\" w:ascii=\"宋体\" w:hAnsi=\"宋体\"/>
					<w:b/>
				</w:rPr>
			</w:pPr>
			<w:r>
				<w:rPr>
					<w:rFonts w:hint=\"eastAsia\" w:ascii=\"宋体\" w:hAnsi=\"宋体\"/>
					<w:b/>
				</w:rPr>
				<w:t>{$title}</w:t>
			</w:r>
		</w:p>";
				
		$this->document_string .= $title_string;
	}
	
	public function setSTitle($stitle)
	{
		$stitle_string = "
		<w:p>
			<w:pPr>
				<w:numPr>
					<w:ilvl w:val=\"0\"/>
					<w:numId w:val=\"2\"/>
				</w:numPr>
				<w:autoSpaceDN w:val=\"0\"/>
				<w:ind w:left=\"709\" w:firstLineChars=\"0\"/>
				<w:rPr>
					<w:rFonts w:hint=\"eastAsia\" w:ascii=\"宋体\" w:hAnsi=\"宋体\"/>
					<w:lang w:val=\"en-US\" w:eastAsia=\"zh-CN\"/>
				</w:rPr>
			</w:pPr>
			<w:r>
				<w:rPr>
					<w:rFonts w:hint=\"eastAsia\" w:ascii=\"宋体\" w:hAnsi=\"宋体\"/>
					<w:lang w:val=\"en-US\" w:eastAsia=\"zh-CN\"/>
				</w:rPr>
				<w:t>{$stitle}</w:t>
			</w:r>
		</w:p>";
				
		$this->document_string .= $stitle_string;
	}

	public function setTTitle($stitle)
	{
		$stitle_string = "
		<w:p>
			<w:pPr>
				<w:numPr>
					<w:ilvl w:val=\"0\"/>
					<w:numId w:val=\"3\"/>
				</w:numPr>
				<w:autoSpaceDN w:val=\"0\"/>
				<w:ind w:left=\"709\" w:firstLineChars=\"0\"/>
				<w:rPr>
					<w:rFonts w:hint=\"eastAsia\" w:ascii=\"宋体\" w:hAnsi=\"宋体\"/>
					<w:lang w:val=\"en-US\" w:eastAsia=\"zh-CN\"/>
				</w:rPr>
			</w:pPr>
			<w:r>
				<w:rPr>
					<w:rFonts w:hint=\"eastAsia\" w:ascii=\"宋体\" w:hAnsi=\"宋体\"/>
					<w:lang w:val=\"en-US\" w:eastAsia=\"zh-CN\"/>
				</w:rPr>
				<w:t>{$stitle}</w:t>
			</w:r>
		</w:p>";
				
		$this->document_string .= $stitle_string;
	}
	
	public function setContent($content)
	{
		$content_string = "
		<w:p>
			<w:pPr>
				<w:autoSpaceDN w:val=\"0\"/>
				<w:ind w:left=\"709\" w:firstLine=\"0\" w:firstLineChars=\"0\"/>
				<w:jc w:val=\"left\"/>
				<w:rPr>
					<w:rFonts w:hint=\"eastAsia\" w:ascii=\"宋体\" w:hAnsi=\"宋体\" w:eastAsia=\"宋体\"/>
					<w:lang w:val=\"en-US\" w:eastAsia=\"zh-CN\"/>
				</w:rPr>
			</w:pPr>
			<w:r>
				<w:rPr>
					<w:rFonts w:hint=\"eastAsia\" w:ascii=\"宋体\" w:hAnsi=\"宋体\" w:eastAsia=\"宋体\"/>
					<w:lang w:val=\"en-US\" w:eastAsia=\"zh-CN\"/>
				</w:rPr>
				<w:t>{$content}</w:t>
			</w:r>
		</w:p>";
				
		$this->document_string .= $content_string;
	}
	
	public function setTail()
	{
		$this->document_string .= '<w:sectPr>
					<w:pgSz w:w="11906" w:h="16838"/>
					<w:pgMar w:top="1440" w:right="1800" w:bottom="1440" w:left="1800" w:header="851" w:footer="992" w:gutter="0"/>
					<w:cols w:space="720" w:num="1"/>
					<w:docGrid w:type="lines" w:linePitch="312" w:charSpace="0"/>
				</w:sectPr>
			</w:body>
		</w:document>';
	}
	
	public function create_doc_xml_file($filename)
	{
		$zip_file = $this->zip_path . '/' . $filename . '.zip';
		
		if ( file_put_contents($this->docxml_path, $this->document_string) ) {
			$ori_dir = getcwd();
			chdir($this->zip_path);
			exec("zip $filename.zip -r ./");
			chdir($ori_dir);
		}
		if ( file_exists($zip_file) ) {
			rename($zip_file, $this->zip_path . '/' . $filename . '.docx');
			exec("mv {$this->zip_path}/{$filename}.docx {$this->docx_path}/{$filename}.docx");
		}

		if ( file_exists($this->docx_path . '/' . $filename . '.docx' ) ) {
			return $this->docx_path . '/' . $filename . '.docx';
		}
	}
	
	public function test()
	{
		die(json_encode($this->document_string));
	}
}
