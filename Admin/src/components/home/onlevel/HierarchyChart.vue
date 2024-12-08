<template>
  <div>
    <h2 class="text-2xl font-bold mt-10">Sơ đồ cấp bậc</h2>
    <div ref="network" class="w-full h-200 bg-white border rounded-lg shadow-md"></div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { Network } from 'vis-network'

// Nhận danh sách quản lý và cấp dưới từ component cha
const props = defineProps({
  managersWithSubordinates: Array
})

// Tham chiếu đến phần tử DOM chứa sơ đồ
const network = ref(null)

const drawHierarchy = () => {
  const nodes = []
  const edges = []

  const existingNodeIds = new Set() // Tập hợp để lưu các id đã tồn tại

  props.managersWithSubordinates.forEach((manager) => {
    // Kiểm tra nếu node của người quản lý đã tồn tại
    if (!existingNodeIds.has(manager.id)) {
      nodes.push({ id: manager.id, label: manager.name + ' - ' + manager.username, shape: 'box' })
      existingNodeIds.add(manager.id) // Thêm id vào tập hợp
    }

    manager.managed_users.forEach((subordinate) => {
      // Kiểm tra nếu node của cấp dưới đã tồn tại
      if (!existingNodeIds.has(subordinate.id)) {
        nodes.push({
          id: subordinate.id,
          label: subordinate.name,
          shape: 'box'
        })
        existingNodeIds.add(subordinate.id) // Thêm id vào tập hợp
      }

      // Thêm các kết nối từ manager tới cấp dưới
      edges.push({ from: manager.id, to: subordinate.id })
    })
  })

  const data = { nodes, edges }
  const options = {
    nodes: {
      color: '#8bc34a',
      font: { size: 14, color: '#333' }
    },
    edges: {
      arrows: 'to',
      color: '#999'
    },
    layout: {
      hierarchical: {
        direction: 'UD',
        sortMethod: 'directed'
      }
    }
  }

  // Tạo và hiển thị mạng lưới với dataset đã kiểm tra trùng lặp
  const networkInstance = new Network(network.value, data, options)
}

onMounted(() => {
  // Vẽ sơ đồ khi component được mount
  drawHierarchy()
})

watch(
  () => props.managersWithSubordinates,
  () => {
    // Vẽ lại sơ đồ khi danh sách quản lý hoặc cấp dưới thay đổi
    drawHierarchy()
  }
)
</script>

<style scoped>
/* Đảm bảo rằng container của sơ đồ có chiều cao nhất định */
.network {
  width: 100%;
  height: 100%;
}

.h-200 {
  height: 1000px;
}
</style>
