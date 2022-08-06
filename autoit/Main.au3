#include <ButtonConstants.au3>
#include <EditConstants.au3>
#include <GUIConstantsEx.au3>
#include <StaticConstants.au3>
#include <WindowsConstants.au3>
#include <MsgBoxConstants.au3>
#include "Core.au3"

;_checkVersion()

$title = "Tool Check Key"
$title2 = 'Form Auto'

; Form
$Form = GUICreate($title, 306, 275, 826, 332, BitOR($GUI_SS_DEFAULT_GUI,$WS_SIZEBOX,$WS_THICKFRAME))
GUISetFont(12, 400, 0, "Tahoma")
$txtKey = GUICtrlCreateLabel("Key", 8, 8, 29, 23)
$inputKey = GUICtrlCreateInput("", 8, 32, 233, 27)
$btnCheckKey = GUICtrlCreateButton("OK", 248, 32, 51, 27)
GUICtrlSetCursor(-1, 0)

$groupInfomation = GUICtrlCreateGroup("Info", 8, 72, 289, 169)

$txtHIWD = GUICtrlCreateLabel("HIWD", 16, 104, 46, 23)
$txtName = GUICtrlCreateLabel("Tên", 16, 136, 45, 23)
$txtExpire = GUICtrlCreateLabel("Hết hạn", 16, 168, 60, 23)
$txtAction = GUICtrlCreateLabel("", 16, 200, 49, 23)

$dataHWID = GUICtrlCreateInput(StringLeft($HWID_TOOL, 10) & "....", 83, 104, 204, 23, BitOR($GUI_SS_DEFAULT_INPUT,$ES_READONLY))
$dataName = GUICtrlCreateInput("N/A", 83, 136, 202, 23, BitOR($GUI_SS_DEFAULT_INPUT,$ES_READONLY))
$dataExpire = GUICtrlCreateInput("N/A", 83, 168, 202, 23, BitOR($GUI_SS_DEFAULT_INPUT,$ES_READONLY))

$btnAction = GUICtrlCreateButton("START", 80, 200, 203, 25)
GUICtrlSetCursor(-1, 0)
GUICtrlSetState(-1, 32)

$txtFooter = GUICtrlCreateLabel("N/A", 216, 248, 80, 20)
GUICtrlSetData(-1, "Version " & $VERSION_CURRENT_TOOL)
GUICtrlSetFont(-1, 10, 400, 0, "Tahoma")

GUICtrlCreateGroup("", -99, -99, 1, 1)
GUISetState(@SW_SHOW)


While 1
	$nMsg = GUIGetMsg()
	Switch $nMsg
		Case $GUI_EVENT_CLOSE
			Exit

		Case $btnAction
			_btnAction()

		Case $btnCheckKey
			_btnCheckKey()
	EndSwitch
WEnd

Func _btnCheckKey()
	$keyAuth = GUICtrlRead($inputKey)
	If $keyAuth = "" Then
		MsgBox(16, $TITLE_MESSAGE_TOOL, "Vui lòng nhập thông tin")
		Return
	EndIf

	Global $KEY = $keyAuth

	$res = _checkKey()
	If $res[0] <> False Then
		GUICtrlSetState($btnCheckKey, 128)
		GUICtrlSetState($inputKey, 128)
		GUICtrlSetState($btnAction, 16)
		GUICtrlSetData($dataName, $res[1].name)
		GUICtrlSetData($dataExpire, $res[1].expire)
	Else
		MsgBox(16, $TITLE_MESSAGE_TOOL, $res[1])
	EndIf
EndFunc

Func _btnAction()
	If _checkAuth() Then
		MsgBox(64, $TITLE_MESSAGE_TOOL, "HELLO WORLD")
		GUICtrlSetState($btnAction, 32)
		_newForm()
	Else
		MsgBox(16, $TITLE_MESSAGE_TOOL, "Key đã hết hạn hoặc không tồn tại")
		Exit
	EndIf
EndFunc

Func _newForm()
	; bạn chèn code sau khi auth vào đây
	; khi anh em muốn check auth có còn hạn không thì cứ sài hàm _checkAuth()

	$newFrom = GUICreate($title2, 473, 302, 192, 124)
	$button1 = GUICtrlCreateButton("Button1", 143, 126, 187, 49)
	GUISetState(@SW_SHOW)

	While 1
		$nMsg = GUIGetMsg()
		Switch $nMsg
			Case $GUI_EVENT_CLOSE
				Exit

			Case $button1
				If _checkAuth() = False Then
					MsgBox(16, $TITLE_MESSAGE_TOOL, "Đã hết hạn")
					Exit
				Else
					MsgBox(64, $TITLE_MESSAGE_TOOL, "Xin chào !!")
				EndIf
		EndSwitch

		$postionNewFrom = WinGetPos($title2)
		$postionAuth = WinGetPos($title)
		WinMove($title, "", $postionNewFrom[0] + $postionNewFrom[2] + 20, $postionNewFrom[1])
	WEnd
EndFunc


