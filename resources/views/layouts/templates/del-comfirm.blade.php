<template>
	<modal v-model="delComfirm" width="360">
		<p slot="header" style="color:#f60;text-align:left">
			<icon type="information-circled"></icon>
			<span>您确定删除该记录?</span>
		</p>
		<div>
			<p>
				记录删除后将无法恢复，请确定删除
			</p>
		</div>
		<div slot="footer">
			<i-button type="error" size="large" long :loading="modal_loading" @click="del">
				删除
			</i-button>
		</div>
	</modal>
</template>