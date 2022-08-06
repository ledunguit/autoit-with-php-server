; CODE ĐƯỢC TẠO BỞI NHAN PHAN - FB.ME/NHAN.PDN
#include "_HttpRequest.au3"
#include <APIDiagConstants.au3>
#include <WinAPIDiag.au3>
#include <String.au3>

; title message box
Global $TITLE_MESSAGE_TOOL = "Thông báo"

; url api, bỏ dấu / phía sau đi nhé
Global $URL_TOOL = "http://localhost:81/autoit_check/api.php"

; HWID anh em muốn theo dạng BIOS, HARDWARE, CPU... thì có thể tự viết lại nha nhưng output cuối cùng nên chuyển về mã hex để cho PRO =))
Global $HWID_TOOL = "0x" &_StringToHex(StringReplace(StringReplace(_WinAPI_UniqueHardwareID(), "{", ""), "{", ""))

; version & update
Global $VERSION_CURRENT_TOOL = '1.0.0' ; version hiện tại của tool

; data auth
Global $KEY = "NULL"
Global $AUTH = False

Func _checkKey()
	$httpRequest = _HttpRequest(2, $URL_TOOL, 'key=' & $KEY & '&hiwd=' & $HWID_TOOL & '&requestTool=true')
	$jsonData = _HttpRequest_ParseJSON($httpRequest)

	$status = $jsonData.status
	$message = $jsonData.message

	If $status = "success" Then
		Global $AUTH = True
		Local $result[2]
		$result[0] = True
		$result[1] = $jsonData.data
		Return $result
	Else
		Global $AUTH = False
		Local $result[2]
		$result[0] = False
		$result[1] = $message
		Return $result
	EndIf
EndFunc

Func _checkAuth()
	_checkKey()
	Return $AUTH
EndFunc

Func _checkVersion()
	$httpRequest = _HttpRequest(2, $URL_TOOL & '?version=request_new_version')
	$jsonData = _HttpRequest_ParseJSON($httpRequest)

	$new = $jsonData.version
	$linkNew = $jsonData.linkNew

	If StringInStr($new, $VERSION_CURRENT_TOOL) = False Then
		$msgbox = MsgBox(4, $TITLE_MESSAGE_TOOL, "Vui lòng tải phiên bản mới nhất tại link này " & $linkNew)
		If $msgbox = 6 Then
			ShellExecute($linkNew)
		EndIf
		Exit
	Else
		MsgBox(64, $TITLE_MESSAGE_TOOL, "Đây là phiên bản mới nhất " & $new)
	EndIf
EndFunc