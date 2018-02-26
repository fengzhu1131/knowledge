<template>
	<modal class="model-add" v-model="modalAdd" :title="item.title||'新增记录'" closable="false">
		<i-form :label-width="80">
			<form-item label="标题">
				<i-input v-model="item.title" placeholder="Enter something..."></i-input>
			</form-item>
			<form-item label="型号">
				<i-select v-model="proModel" filterable>
					<i-option dis-hover v-for="(item, index) in proModels" :key="index" :value="item" v-text="item"></i-option>
				</i-select>
			</form-item>
			<form-item label="">
				<radio-group v-model="item.ispublish">
					<radio label="true">
						发布
					</radio>
					<radio label="false">
						不发布
					</radio>
				</radio-group>
			</form-item>
			<form-item label="">
				<row>
					<i-col span="6">
						<form-item prop="date">
							<tree :data="item.tree" :render="renderContent"></tree>
						</form-item>
					</i-col>
					<i-col span="18">
						<form-item label="">
							<script id="editor" name="content" v-model="item.content" type="text/plain"></script>
						</form-item>
					</i-col>
				</row>
		</i-form>
	</modal>
</template>